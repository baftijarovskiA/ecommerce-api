<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GenericRolePermController extends Controller
{
    public function allRoles(){
        $roles = Role::all();
        if ($roles->count() > 0){
            $data = array(['data'=>$roles, 'total'=>$roles->count()]);
            return response()->json($data,200);
        }
        return response()->json("Data not found",400);
    }

    public function findRole($id){
        if (!is_numeric($id)){
            return response()->json("Bad request", 400);
        }
        $role = Role::find($id);
        if ($role == null){
            return response()->json("Data not found",400);
        }
        return response()->json($role,200);
    }

    public function createRole(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'required|string|max:255',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $role = new Role();
        $role->name = strtolower($request->input('name'));
        $role->display_name = strtolower($request->input('name'));
        $role->description = $request->input('description');
        $role->save();

        return response()->json("Role created", 200);

    }

    public function editRole(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $role = Role::find($id);
        $role->name = strtolower($request->input('name'));
        $role->display_name = strtolower($request->input('name'));
        $role->description = $request->input('description');
        $role->save();

        return response()->json("Role updated", 200);
    }

    public function allPermissions(){
        $roles = Permission::all();
        if ($roles->count() > 0){
            $data = array(['data'=>$roles, 'total'=>$roles->count()]);
            return response()->json($data,200);
        }
        return response()->json("Data not found",400);
    }

    public function findPermission($id){
        if (!is_numeric($id)){
            return response()->json("Bad request", 400);
        }
        $role = Permission::find($id);
        if ($role == null){
            return response()->json("Data not found",400);
        }
        return response()->json($role,200);
    }


    public function createPermission(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:permissions',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $permission = new Permission();
        $permission->name = str_replace(" ","-",strtolower($request->input('name')));
        $permission->display_name = strtolower($request->input('name'));
        $permission->description = strtolower($request->input('name'));
        $permission->save();

        return response()->json("Permission created",200);

    }

    public function editPermission(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:permissions',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $permission = Permission::find($id);
        $permission->name = str_replace(" ","-",strtolower($request->input('name')));
        $permission->display_name = strtolower($request->input('name'));
        $permission->description = strtolower($request->input('name'));
        $permission->save();

        return response()->json("Permission updated",200);
    }

    public function assignRole(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'role' => 'required|string|max:255'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::where('email', '=', $request->input('email'))->first();
        $role = Role::where('name', '=', $request->input('role'))->first();

        if ($user == null){
            return response()->json("User does not exist", 400);
        }
        if ($role == null){
            return response()->json("Role does not exist", 400);
        }

        $userRoles = $user->roles()->get();
        foreach ($userRoles as $userRole){
            if ($role->name == $userRole->name){
                return response()->json("User ".$user->email.", already has that role", 400);
            }
        }

        $user->roles()->attach($role->id);
        return response()->json("Role assigned successfully", 200);
    }

    public function attachPermission(Request $request){

        $validator = Validator::make($request->all(), [
            'role' => 'required|string|max:255',
            'name' => 'required|string|max:255'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $role = Role::where('name', '=', $request->input('role'))->first();
        $permission = Permission::where('name', '=', $request->input('name'))->first();
        $role->attachPermission($permission);

        return response()->json("Permission attached", 200);
    }

    public function roleDetails(){
        $roles = Role::all();
        $roles_permission = DB::table('permission_role')->get();
        foreach ($roles as $role){
            $data = array();
            foreach ($roles_permission as $item){
                if ($role->id == $item->role_id){
                    array_push($data, Permission::find($item->permission_id));
                    $role['permissions'] = $data;
                }
            }
        }
        return response()->json($roles,200);
    }
}
