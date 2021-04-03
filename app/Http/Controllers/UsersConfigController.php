<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UsersConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware(['agent']);
    }
    
    public function users()
    {
        $users = User::all();

        // $users = DB::table('users')
        //     ->join('role_user','users.id','=','role_user.user_id')
        //     ->get();
        
        return view('config.users', compact('users'));
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();

        $remove_role = DB::table('role_user')
            ->where('user_id',$user->id)
            ->delete();

        return back();

    }

    public function upgrade(Request $request)
    {
        $users = $request->input('upgarde');
        dd($users);
    }

    

    
}
