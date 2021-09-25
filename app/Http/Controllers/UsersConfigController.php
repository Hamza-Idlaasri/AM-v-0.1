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
        $all_users = User::all()->except(1);

        // Upgrade ----------------------
        $users_upgraded = $request->users;

        if($users_upgraded)
        {
            foreach ($all_users as $user) {

                if(in_array($user->id,$users_upgraded))
                {
                    
                    if(User::find($user->id)->hasRole('agent'))
                        continue;
                    else
                    {
                        User::find($user->id)->detachRole('superviseur');
                        User::find($user->id)->attachRole('agent');
                    }
                    
                } 
                else {
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

        // Notified ---------------------
        $users_notified = $request->notified;
        
        if($users_notified)
        {

            foreach ($all_users as $user) {

                if (in_array($user->id,$users_notified)) {

                    if($user->notified)
                        continue;
                    else
                        User::find($user->id)->update(['notified' => 1]);

                }
                else {

                    if(!$user->notified)
                        continue;
                    else 
                        User::find($user->id)->update(['notified' => 0]);
                
                }
                
            }

        } else
        {

            foreach ($all_users as $user) {
                
                if(!$user->notified)
                    continue;
                else
                    User::find($user->id)->update(['notified' => 0]);

            }
            
        }

        return back();
    }

}
