<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Banners;
use App\Models\Bookings;
use App\Models\Constants;
use App\Models\Coupons;
use App\Models\FaqCats;
use App\Models\Faqs;
use App\Models\GlobalFunction;
use App\Models\GlobalSettings;
use App\Models\PlatformEarningHistory;
use App\Models\SalonCategories;
use App\Models\SalonNotifications;
use App\Models\SalonPayoutHistory;
use App\Models\SalonReviews;
use App\Models\Salons;
use App\Models\Taxes;
use App\Models\UserNotification;
use App\Models\UserWalletRechargeLogs;
use App\Models\UserWithdrawRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Google\Client;
use Illuminate\Support\Facades\File;
Use DB;
use App\Models\Language;
use App\Models\LanguageContent;
use App\Models\User;
use App\Models\EmailTemplate;
use App\Models\Fee;
use App\Models\NotificationTemplate;
use App\Models\SupportTicket;
use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionRole;
use Illuminate\Support\Facades\Session;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::get();
        return view('roles.index', ['data' => $roles]);
    }
    
    public function create()
    {
        $permissions = Permission::where(['user_type' => 'Admin'])->select('id', 'group', 'display_name','user_type')->get();

        $perm = [];
        if (!empty($permissions))
        {
            foreach ($permissions as $key => $value)
            {
                $perm[$value->group][$key]['id']           = $value->id;
                $perm[$value->group][$key]['display_name'] = $value->display_name;
                $perm[$value->group][$key]['user_type'] = $value->user_type;
            }
        }
        $perm = $perm;
            
        return view('roles.create', ['permissions' => $permissions, 'perm' => $perm]);
    }
    
    public function store(Request $request)
    {
        $role               = new Role();
        $role->name         = $request->name;
        $role->display_name = $request->display_name;
        $role->description  = $request->description;
        $role->status       = $request->status;
        $role->user_type    = 'Admin';
        $role->save();

        foreach ($request->permission as $key => $value)
        {
            PermissionRole::firstOrCreate(['permission_id' => $value, 'role_id' => $role->id]);
        }
        
        return redirect('roles')->with(['roles_message' => 'New Role Added successfully']);
    }
    
    function list(Request $request)
    {
        $totalData =  Role::count();
        $rows = Role::orderBy('id', 'ASC')->get();

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
            $result = Role::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Role::where(function ($query) use ($search) {
                    $query->Where('name', 'LIKE', "%{$search}%")
                        ->orWhere('display_name', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Role::where(function ($query) use ($search) {
                    $query->Where('name', 'LIKE', "%{$search}%")
                        ->orWhere('display_name', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();

        $settings = GlobalSettings::first();
        
        foreach ($result as $k=>$item) 
        {
            $view = '<a href="' . route('roles.edit', $item->id) . '" class="mr-2 btn btn-info text-white " rel=' . $item->id . ' >' . __("Edit") . '</a>';

            $delete = '<a href="" class="mr-2 btn btn-danger text-white delete" rel=' . $item->id . ' >' . __("Delete") . '</a>';

            if($item->id == '1'){
                $action = $view;
            }else{
                $action = $view.$delete;
            }
            
            if($item->status == '1'){
                $status = 'Active';
            }else{
                $status = 'Inactive';
            }

            $data[] = array(
                ++$k,
                $item->name,
                $item->display_name,
                $item->description,
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
    
    public function edit($id)
    {
        $settings = GlobalSettings::first();
        
        $result = Role::find($id);
        $stored_permissions = Role::permission_role($id)->toArray();
        $allpermissions = Permission::where(['user_type' => 'Admin'])->select('id', 'group', 'display_name','user_type')->get();

        $perm = [];
        if (!empty($allpermissions))
        {
            foreach ($allpermissions as $key => $value)
            {
                $perm[$value->group][$key]['id']           = $value->id;
                $perm[$value->group][$key]['display_name'] = $value->display_name;
                $perm[$value->group][$key]['user_type'] = $value->user_type;
            }
        }
        
        $permissions = $perm;

        return view('roles.edit', [
            'settings' => $settings,
            'result' => $result,
            'stored_permissions' => $stored_permissions,
            'permissions' => $permissions,
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $role               = Role::find($id);
        $role->name         = $request->name;
        $role->display_name = $request->display_name;
        $role->description  = $request->description;
        $role->status       = $request->status;
        $role->user_type    = 'Admin';
        $role->save();

        $stored_permissions = Role::permission_role($id);

        foreach ($stored_permissions as $key => $value)
        {
            if (!in_array($value, $request->permission))
            {
                PermissionRole::where(['permission_id' => $value, 'role_id' => $id])->delete();
            }
        }
        foreach ($request->permission as $key => $value)
        {
            PermissionRole::firstOrCreate(['permission_id' => $value, 'role_id' => $id]);
        }

        return redirect('roles')->with(['roles_message' => 'Role Updated successfully']);
    }
    
    public function delete(Request $request, $id)
    {
        Role::where('id', $request->id)->delete();
        PermissionRole::where('role_id', $request->id)->delete();

        return back()->with(['roles_message' => 'Role Deleted successfully']);
    }
}
