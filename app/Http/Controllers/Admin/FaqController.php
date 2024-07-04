<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Language;
use App\Models\Blog;
use App\Models\Admin;
use App\Models\GlobalFunction;
use App\Models\GlobalSettings;
use App\Models\MaintenanceSetting;
use App\Jobs\SendEmail;
use App\Models\Users;
use App\Models\EmailTemplate;
use App\Models\Faqs;
use App\Models\FaqCats;

class FaqController extends Controller
{
    public function index()
    {
        $data['languages'] = Language::get();
        $data['faqs'] = Faqs::orderBy('id', 'desc')->get();
        return view('faqs.index', $data);
    }

    function list(Request $request)
    {
        $totalData =  Faqs::count();
        $rows = Faqs::orderBy('id', 'DESC')->get();

        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname',
            2 => 'identity',
            3 => 'username',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = Faqs::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Faqs::Where('question_en', 'LIKE', "%{$search}%")
                ->orWhere('answer_en', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Faqs::Where('question_en', 'LIKE', "%{$search}%")
                ->orWhere('answer_en', 'LIKE', "%{$search}%")
                ->count();
        }
        $data = array();
        $i=1;
        foreach ($result as $item) {
            if(has_permission(session()->get('user_type'), 'edit_faqs')){
                $edit = '<a href="' . route('faqs.edit', $item->id) . '" class="mr-2 btn btn-primary text-white editBtn"><i class="fa fa-edit"></i></a>';
            }else{
                $edit = '';
            }
            
            if(has_permission(session()->get('user_type'), 'delete_faqs')){
                $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->id . ' ><i class="fa fa-trash"></i></a>';
            }else{
                $delete = '';
            }
            
            $action = $edit . $delete;

            $category = '<span class="badge bg-primary text-white">' . $item->category->title . '</span>';

            $data[] = array(
                $i++,
                $item->question_en,
                $item->answer_en,
                $category,
                $action,
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
        echo json_encode($json_data);
        exit();
    }
    
    public function create()
    {
        $data['languages'] = Language::get();
        $data['cats'] = FaqCats::all();
        return view('faqs.create', $data);
    }
    
    function store(Request $request)
    {
        $faq = new Faqs();
        $faq->category_id = $request->category_id;
        $faq->question_en = $request->question_en;
        $faq->question_pap = $request->question_pap;
        $faq->question_nl = $request->question_nl;
        $faq->answer_en = $request->answer_en;
        $faq->answer_pap = $request->answer_pap;
        $faq->answer_nl = $request->answer_nl;
        $faq->status = $request->status;
        $faq->save();
        return redirect('faqs')->with(['faq_message' => 'FAQ Added Successfully']);
    }
    
    public function edit($id)
    {
        $data['languages'] = Language::get();
        $data['faq'] = Faqs::where('id', $id)->first();
        $data['cats'] = FaqCats::all();
        return view('faqs.edit', $data);
    }
    
    function update(Request $request, $id)
    {
        $faq = Faqs::find($id);
        $faq->category_id = $request->category_id;
        $faq->question_en = $request->question_en;
        $faq->question_pap = $request->question_pap;
        $faq->question_nl = $request->question_nl;
        $faq->answer_en = $request->answer_en;
        $faq->answer_pap = $request->answer_pap;
        $faq->answer_nl = $request->answer_nl;
        $faq->status = $request->status;
        $faq->save();
        return redirect('faqs')->with(['faq_message' => 'FAQ Updated Successfully']);
    }

    function delete($id)
    {
        $faqCat = Faqs::find($id);
        $faqCat->delete();
        return back()->with(['faq_message' => 'FAQ Deleted Successfully']);
    }
    
    function faqs_cat_list(Request $request)
    {
        $totalData =  FaqCats::count();
        $rows = FaqCats::orderBy('id', 'DESC')->get();

        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname',
            2 => 'identity',
            3 => 'username',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = FaqCats::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  FaqCats::Where('title', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = FaqCats::Where('title', 'LIKE', "%{$search}%")
                ->count();
        }
        $data = array();
        $i=1;
        foreach ($result as $item) {
            
            if(has_permission(session()->get('user_type'), 'view_faqs_category')){
                $edit = '<a data-title="' . $item->title . '" href="" class="mr-2 btn btn-primary text-white edit editBtn" rel=' . $item->id . ' ><i class="fa fa-edit"></i></a>';
            }else{
                $edit = '';
            }
            
            if(has_permission(session()->get('user_type'), 'delete_faqs_category')){
                $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->id . ' ><i class="fa fa-trash"></i></a>';
            }else{
                $delete = '';
            }
            
            $action = $edit . $delete;

            $data[] = array(
                $i++,
                $item->title,
                $action,
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
        echo json_encode($json_data);
        exit();
    }
    
    function faqs_cat_store(Request $request)
    {
        $faqCat = new FaqCats();
        $faqCat->title = $request->title;
        $faqCat->save();
        return GlobalFunction::sendSimpleResponse(true, 'Category added successfully');
    }
    
    function faqs_cat_update(Request $request)
    {
        $faqCat = FaqCats::find($request->id);
        $faqCat->title = $request->title;
        $faqCat->save();
        return GlobalFunction::sendSimpleResponse(true, 'Category edited successfully');
    }
    
    function faqs_cat_delete($id)
    {
        $faqCat = FaqCats::find($id);
        $faqCat->delete();
        Faqs::where('category_id', $id)->delete();
        return back()->with(['faq_message' => 'FAQ Category Deleted Successfully']);
    }
    
    function faqs_cat()
    {
        $cats = FaqCats::all();
        return GlobalFunction::sendDataResponse(true, 'cats fetched successfully!', $cats);
    }
}

