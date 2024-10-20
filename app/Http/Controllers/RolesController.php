<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Permission;
use RealRashid\SweetAlert\Facades\Alert;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        if($request->ajax()){
            
            return $this->getRoles();
        }
        return view('users.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permission = Permission::get();
        return view('users.roles.create')->with(['Permissions'=>$permission]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validate Names
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required'
        ]);
        $role = Role::create(['name' => strtolower(trim($request->name))]);
        $role->syncPermissions($request->permission);
        if ($role) {
            Alert::success('Success', 'New role saved successfully!');
            return view('users.roles.index');
        }
        Alert::error('Failed','Role not saved. Try again later!');
        return redirect()->back()->withInput();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Role $role)
    {
         if($request->ajax()){
            return $this->getRolesPermissions($role);
         }
        return view('users.roles.show')->with(['role' => $role]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Role $role)
    {
        return view('users.roles.edit')->with(['role' => $role]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Role $role, Request $request)
    {
        $this->validate($request, [
            'name' => 'requred',
            'permission' => 'requred',
        ]);
        $role->update($request->only('name'));
        $role->syncPermissions($request->permission);
        if ($role) {
            Alert::success('Success', 'Role updated successfully!');
            return view('users.roles.index');
        }
        Alert::error('Failed', 'Role not updated. Try again later!');
        return redirect()->back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Role $role)
    {
        if($request->ajax() && $role->delete()){
            return response(["message" => "Role deleted successfully"], 200);
        }
        return response(["message" => "Role delete error! Please try again later"], 201);
    }

    private function getRoles(){
        $data = Role::withCount('users','permissions')->get();
        return DataTables::of($data)
        ->addColumn('name', function($row){
            return ucfirst($row->name);
        })
        ->addColumn('users_count', function($row){
            return $row->users_count;
        })
        ->addColumn('permissions_count', function($row){
            return $row->permissions_count;
        })
        ->addColumn('action', function($row){
            $action = "";
            $action .= '<a class="btn btn-xs btn-primary mr-1" href=' . route('users.roles.show', $row->id) . ' id="btnShow"><i class="fas fa-eye"></i>View </a>';
            $action .= '<a class="btn btn-xs btn-warning mr-1" href=' . route('users.roles.edit', $row->id) . ' id="btnEdit"><i class="fas fa-edit"></i>Edit </a>';
            $action .= '<button class="btn btn-xs btn-danger" id="btnDelete" data-id=' . $row->id . '><i class="fas fa-trash"></i>Delete</button>';
            return $action;
        })->make('true');
    }

    private function getRolesPermissions($role)
    {
        $permissions = $role->permissions;
        return DataTables::of($permissions)->make(true); 
    }
}