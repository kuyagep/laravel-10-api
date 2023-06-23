<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         if($request->ajax()){
             return $this->getUsers();
         }
        return view('users.index')->with(['roles'=>Role::get()]);
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
    public function store(Request $request, User $user)
    {
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|email:rfc, dns|unique:users,email',
        ]);

        if ($request->has('roles')) {
           $user->create($request->all())->roles()->sync($request->roles);
        }else{
             $user->create($request->all());
        }
         if ($user) {
            Alert::success('Success', 'User saved successfully!');
             return view('users.index')->with(['roles'=>Role::get()]);
        }
        Alert::error('Failed', 'User not saved. Try again later!');
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
    public function edit(string $id)
    {
        //
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
    public function destroy(Request $request, User $user)
    {
        if($request->ajax() && $user->delete()){
            return response(["message" => "User deleted successfully"], 200);
        }
        return response(["message" => "User delete error! Please try again later"], 201);
    }
    private function getUsers()
    {   
        $data = User::with('roles')->get();
        return DataTables::of($data)
        ->addColumn('name', function($row){
            return ucfirst($row->name);
        })
        ->addColumn('date', function($row){
            return Carbon::parse( $row->created_at)->format('d M, Y h:i:s A');
        })
        ->addColumn('roles', function($row){
            $role = "";
            if($row->roles != null){
                foreach ($row->roles as $next) {
                    $role.= '<span class="badge badge-primary">'.ucfirst($next->name).'</span>';
                }
            }
            return $role;
        })
        ->addColumn('action', function($row){
            $action = "";
            $action .= '<a class="btn btn-xs btn-primary mr-1" href=' . route('users.roles.show', $row->id) . ' id="btnShow"><i class="fas fa-eye"></i>View </a>';
            $action .= '<a class="btn btn-xs btn-warning mr-1" href=' . route('users.roles.edit', $row->id) . ' id="btnEdit"><i class="fas fa-edit"></i>Edit </a>';
            $action .= '<button class="btn btn-xs btn-danger" id="btnDelete" data-id=' . $row->id . '><i class="fas fa-trash"></i>Delete</button>';
            return $action;
        })->rawColumns(['name','date','roles', 'action'])->make(true);
    }
}