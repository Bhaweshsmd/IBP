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

class BlogController extends Controller
{
    function index()
    {
        $blogs = Blog::where('status', '1')->get();
        return view('blogs.index',['blogs'=> $blogs]);
    }
    
    function list(Request $request)
    {
        $totalData =  Blog::count();
        $rows = Blog::orderBy('id', 'DESC')->get();

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
            $result = Blog::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result = Blog::where(function ($query) use ($search) {
                    $query->Where('en_title', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
                $totalFiltered = Blog::where(function ($query) use ($search) {
                    $query->Where('en_title', 'LIKE', "%{$search}%")
                        ->orWhere('status', 'LIKE', "%{$search}%");
                })
                ->count();
           
            if(!$totalFiltered){
                $result =  Blog::where('en_title', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
                
                $totalFiltered = Blog::where('en_title', 'LIKE', "%{$search}%")
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
            
            $admin_detials = Admin::where('user_name',session()->get('user_name'))->first();
            
            if ($item->image == null) {
                $image = '<img src="https://placehold.jp/150x150.png" width="100px>';
            } else {
                $imgUrl = GlobalFunction::createMediaUrl($item->image);
                $image = '<img src="' . $imgUrl . '" width="100px">';
            }
            
            if(has_permission(session()->get('user_type'), 'view_blogs')){
                $view = '<a href="' . route('web.pages', $item->slug) . '" target="_blank" class="mr-2 btn btn-success text-white viewBtn" rel=' . $item->id . ' ><i class="fa fa-eye"></i></a>';
            }else{
                $view = '';
            }
            
            if(has_permission(session()->get('user_type'), 'edit_blogs')){
                $edit = '<a href="' . route('blog.edit', $item->id) . '" class="mr-2 btn btn-info text-white editBtn" rel=' . $item->id . ' ><i class="fa fa-edit"></i></a>';
            }else{
                $edit = '';
            }
            
            if(has_permission(session()->get('user_type'), 'delete_blogs')){
                $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->id . ' ><i class="fa fa-trash faPostion"></i></a>';
            }else{
                $delete = '';
            }
            
            $action = $view.$edit.$delete;
            
            $data[] = array(
                ++$k,
                $image,
                $item->en_title,
                $admin_detials->first_name.' '.$admin_detials->last_name,
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
        return view('blogs.create',['languages'=> $languages]);
    }
    
    function store(Request $request)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->en_title)));
        
        $admin_detials = Admin::where('user_name',session()->get('user_name'))->first();
        
        if ($request->has('image')) {
            $image = GlobalFunction::saveFileAndGivePath($request->image);
        }else{
            $image = null;
        }
        
        $rs = Blog::create([
            "en_title" => $request->en_title,
            "en_description" => $request->en_description,
            "pap_title" => $request->pap_title,
            "pap_description" => $request->pap_description,
            "nl_title" => $request->nl_title,
            "nl_description" => $request->nl_description,
            "status" => $request->status,
            "added_by" => $admin_detials->user_id,
            "slug" => $slug,
            "image" => $image,
        ]);
        
        return redirect('blogs')->with(['blog_message' => 'Blog Created Successfully']);
    }
    
    function edit($id)
    {
        $languages = Language::get();
        $blogs = Blog::where('id', $id)->first();
        return view('blogs.edit',['blogs'=> $blogs, 'languages'=> $languages]);
    }
    
    function update(Request $request, $id)
    {
        $admin_detials = Admin::where('user_name',session()->get('user_name'))->first();
        
        if ($request->has('image')) {
            $image = GlobalFunction::saveFileAndGivePath($request->image);
        }else{
            $blog_image = Blog::where('id', $id)->first();
            $image = $blog_image->image;
        }
        
        $rs = Blog::where('id', $id)->update([
            "en_title" => $request->en_title,
            "en_description" => $request->en_description,
            "pap_title" => $request->pap_title,
            "pap_description" => $request->pap_description,
            "nl_title" => $request->nl_title,
            "nl_description" => $request->nl_description,
            "status" => $request->status,
            "added_by" => $admin_detials->user_id,
            "slug" => $request->slug,
            "image" => $image,
        ]);
        
        return redirect('blogs')->with(['blog_message' => 'Blog Updated Successfully']);
    }
    
    function delete(Request $request, $id)
    {
        $rs = Blog::where('id', $id)->delete();
        
        return redirect('blogs')->with(['blog_delete' => 'Blog Deleted Successfully']);
    }
}
