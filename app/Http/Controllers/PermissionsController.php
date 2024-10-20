<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;
class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()){
           return $this->getPermissions($request->role_id);
            
        }
        return view('users.permissions.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request->all();
        //Valaidate name
        $this->validate($request ,[
            'name'=>'required|unique:permissions,name',
        ]);
        $permission = Permission::create(['name'=>strtolower($request->name)]);
        if ($permission) {
            Alert::success('Success', 'New permission saved successfully!');
            return view('users.permissions.index');
        }else{
            Alert::error('Failed', 'Permission not saved. Try again later!');
        }
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
    public function edit(Permission $permission)
    {
        return view('users.permissions.edit')->with(['permission'=>$permission]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $this->validate($request,[
            "name" => 'required|unique:permissions,name,'. $permission->id
        ]);
        
        if ($permission->update($request->only('name')) ) {
            Alert::success('Success', 'Permission updated successfully');
            return view('users.permissions.index');
        }

        Alert::error('Failed', 'Permission not updated. Try again later!');
        return redirect()->back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Permission $permission)
    {
        if($request->ajax() && $permission->delete()){
            return response(["message" => "Permission deleted successfully"], 200);
        }
        return response(["message" => "Permission delete error! Please try again later"], 201);
    }

    private function getPermissions($role_id){
        $data = Permission::get();
        return DataTables::of($data, $role_id)
        ->addColumn('chkBox', function($row) use ($role_id) {
            if ($row->name == "dashboard") {
                return $chkBox = '<input type="checkbox" name="permission['.$row->name.']" value="'.$row->name.'" class="permission" checked onclick="return false;">';
            }else{
                if($role_id != ""){
                    $role = Role::where('id', $role_id)->first();
                    $rolePermission = $role->permissions->pluck('name')->toArray();
                    if(in_array($row->name, $rolePermission)){
                        return $chkBox = '<input type="checkbox" name="permission[' . $row->name . ']" value="' . $row->name . '" class="permission" checked >';
                    }
                }
                return $chkBox = '<input type="checkbox" name="permission[' . $row-> name . ']" value="'.$row->name.'" class="permission">';
            }
            
            
        })
        ->addColumn('action', function ($row) {
            $action = '<a class="btn btn-xs btn-warning mr-1" href="'.route("users.permissions.edit",$row->id).'" id="btnEdit"><i class="fas fa-edit"></i>Edit</a>';
            $action .= '<button class="btn btn-xs btn-danger" id="btnDelete" data-id="'.$row->id .'"><i class="fas fa-trash"></i>Delete</button>';
            return $action;
        })->rawColumns(['chkBox', 'action'])->make(true);
    }
}