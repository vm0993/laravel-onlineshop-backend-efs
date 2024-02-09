<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $results = User::paginate(10);
        $title   = "All User";
        return view('pages.users.index',compact('results','title'));
    }

    public function create()
    {
        $result = "";
        $title  = 'New User';
        return view('pages.users.create',compact('result','title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role'     => 'required|in:admin,staff,user',
        ]);

        DB::beginTransaction();
        try {
            $user           = new User;
            $user->name     = $request->name;
            $user->email    = $request->email;
            $user->password = $request->password;
            $user->role     = $request->role;
            $user->save();

            DB::commit();
            return redirect()->route('users.index')->with('success','User successfull to saved!');
        } catch (\Throwable $th) {
            //dump error variable from $th
            DB::rollBack();
            return redirect()->back()->with('error','User failed to saved!');
        }
    }

    public function edit($id)
    {
        $result = User::find($id);
        $title  = 'Edit User';
        return view('pages.users.create',compact('result','title'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required|in:admin,staff,user',
        ]);

        DB::beginTransaction();
        try {
            $user = User::find($id);
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ];
            $user->update($data);

            if ($request->password) {
                $updPass = [
                    'password' => $request->password,
                ];
                $user->update($updPass);
            }
            DB::commit();
            return redirect()->route('users.index')->with('success','User successfull to updated!');
        } catch (\Throwable $th) {
            //dump error variable from $th
            DB::rollBack();
            return redirect()->back()->with('error','User failed to updated!');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = User::find($id);
            $user->delete();
            DB::commit();
            return redirect()->route('users.index')->with('success','User successfull to deleted!');
        } catch (\Throwable $th) {
            //dump error variable from $th
            DB::rollBack();
            return redirect()->back()->with('error','User failed to deleted!');
        }
    }
}
