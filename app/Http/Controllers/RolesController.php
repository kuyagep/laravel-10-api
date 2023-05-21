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
        Alert::error('Error','Role not saved. Try again later!');
        return redirect()->back()->withInput();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Role $role)
    {
        return view('user.roles.edit')->with(['role' => $role]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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
            $action .= '<a class="btn btn-xs btn-warning mr-1" href=' . route('users.roles.edit', $row->id) . ' id="btnEdit"><i class="fas fa-edit"></i>Edit</a>';
            $action .= '<button class="btn btn-xs btn-danger" id="btnDelete" data-id=' . $row->id . '><i class="fas fa-trash"></i>Delete</button>';
            return $action;
        })->make('true');
    }
}