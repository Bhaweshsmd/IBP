<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Pages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Language;
use App\Models\Page;

class PagesController extends Controller
{
    function index()
    {
        $pages = Page::where('status', '1')->get();
        return view('pages.index',['pages'=> $pages]);
    }
    
    function pages_list(Request $request)
    {
        $totalData =  Page::count();
        $rows = Page::orderBy('id', 'DESC')->get();

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
            $result = Page::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result = Page::where(function ($query) use ($search) {
                    $query->Where('en_title', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
                $totalFiltered = Page::where(function ($query) use ($search) {
                    $query->Where('en_title', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "%{$search}%");
                })
                ->count();
           
            if(!$totalFiltered){
                $result =  Page::where('en_title', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
                
                $totalFiltered = Page::where('en_title', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->count();
            }       
        }
        
        $data = array();
        foreach ($result as $k=>$item) 
        {
            if($item->status == '1'){
                $status = 'Active';
            }else{
                $status = 'Inactive';
            }
            
            if($item->type == '1'){
                $type = 'Web';
            }elseif($item->type == '2'){
                $type = 'App';
            }else{
                $type = 'Both';
            }
            
            if(has_permission(session()->get('user_type'), 'view_pages')){
                $view = '<a href="' . route('web.pages', $item->slug) . '" target="_blank" class="mr-2 btn btn-success text-white viewBtn" rel=' . $item->id . ' ><i class="fa fa-eye"></i></a>';
            }else{
                $view = '';
            }
            
            if(has_permission(session()->get('user_type'), 'edit_pages')){
                $edit = '<a href="' . route('page.edit', $item->id) . '" class="mr-2 btn btn-info text-white editBtn" rel=' . $item->id . ' ><i class="fa fa-edit"></i></a>';
            }else{
                $edit = '';
            }
            
            if(has_permission(session()->get('user_type'), 'delete_pages')){
                $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->id . ' ><i class="fa fa-trash faPostion"></i></a>';
            }else{
                $delete = '';
            }
            
            $action = $view.$edit.$delete;
            
            $data[] = array(
                ++$k,
                $item->en_title,
                $type,
                $status,
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
    
    function create()
    {
        $languages = Language::get();
        return view('pages.create',['languages'=> $languages]);
    }
    
    function store(Request $request)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->en_title)));
        
        $rs = Page::create([
            "en_title" => $request->en_title,
            "en_description" => $request->en_description,
            "pap_title" => $request->pap_title,
            "pap_description" => $request->pap_description,
            "nl_title" => $request->nl_title,
            "nl_description" => $request->nl_description,
            "status" => $request->status,
            "type" => $request->type,
            "slug" => $slug,
        ]);
        
        return redirect('pages')->with(['page_message' => 'Page Created Successfully']);
    }
    
    function edit($id)
    {
        $languages = Language::get();
        $pages = Page::where('id', $id)->first();
        return view('pages.edit',['pages'=> $pages, 'languages'=> $languages]);
    }
    
    function update(Request $request, $id)
    {
        $rs = Page::where('id', $id)->update([
            "en_title" => $request->en_title,
            "en_description" => $request->en_description,
            "pap_title" => $request->pap_title,
            "pap_description" => $request->pap_description,
            "nl_title" => $request->nl_title,
            "nl_description" => $request->nl_description,
            "status" => $request->status,
            "type" => $request->type,
            "slug" => $request->slug,
        ]);
        
        return redirect('pages')->with(['page_message' => 'Page Updated Successfully']);
    }
    
    function delete(Request $request, $id)
    {
        $rs = Page::where('id', $id)->delete();
        
        return redirect('pages')->with(['page_delete' => 'Page Deleted Successfully']);
    }
}
