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
        $users = User::all()->except(1);

        // $users = DB::connection('mysql2')->table('users')
        //     ->join('role_user','users.id','=','role_user.user_id')
        //     ->get();
        
        return view('config.users', compact('users'));
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();

        $remove_role = DB::connection('mysql2')->table('role_user')
            ->where('user_id',$user->id)
            ->delete();

        return back();

    }

    public function upgrade(Request $request)
    {

        $users_upgraded = $request->users;

        // $all_users = User::all()->except(1);
        $all_users = User::all()->except(1);

        $groupA = [];
        $groupS = [];

        if($users_upgraded)
        {
            foreach ($all_users as $user) {

                if(array_search($user->id,$users_upgraded) !== false)
                {
                    
                    if(User::find($user->id)->hasRole('agent'))
                        continue;
                    else
                    {
                        User::find($user->id)->detachRole('superviseur');
                        User::find($user->id)->attachRole('agent');
                    }
                    
                } else
                {
                    if(User::find($user->id)->hasRole('superviseur'))
                        continue;
                    else
                    {
                        User::find($user->id)->detachRole('agent');
                        User::find($user->id)->attachRole('superviseur');
                    }
                    
                }
                
            }

        } else
        {
            foreach ($all_users as $user) {
                
                if(User::find($user->id)->hasRole('superviseur'))
                    continue;
                else
                {
                    User::find($user->id)->detachRole('agent');
                    User::find($user->id)->attachRole('superviseur');
                }
            }
            
        }

        return back();
    }

}
