<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function indexPass()
    {
        $user = auth()->user();
        
        return view('user.changepass', compact('user'));
    }

    public function userProfile()
    {
        $userProfile = auth()->user();

        return view('user.userprofile', compact('userProfile'));
    }

    public function deleteMyAccount($id)
    {
    
        $user = User::find($id);
        $user->delete();

        $remove_role = DB::connection('mysql2')->table('role_user')
            ->where('user_id',$user->id)
            ->delete();

        auth()->logout();

        return redirect()->route('login');
    }

    public function changePassword(Request $request)
    {
        
        if (Hash::check($request->oldPassword, auth()->user()->password)) {

            // validation
            $this->validate($request,[

                'password' => 'required|string|confirmed|min:5|max:12|regex:/^[a-zA-Z0-9-_().@$=%&#+{}*ÀÂÇÉÈÊÎÔÛÙàâçéèêôûù]/|unique:mysql2.users',

            ]);

            // Update user 
            auth()->user()->update([

                'password' => Hash::make($request->password),

            ]);
            

        } else {
            return back()->with('status','Invalid Old Password');
        }

        return redirect()->route('userProfile')->with('status','Password changed');
    }

    public function indexInfo()
    {
        $user = auth()->user();
        
        return view('user.editinfo', compact('user'));
    }

    public function changeNameEmail(Request $request)
    {
        // validation
        $this->validate($request,[

            'username' => 'required|min:3|max:15|regex:/^[a-zA-Z][a-zA-Z0-9-_(). ÀÂÇÉÈÊÎÔÛÙàâçéèêôûù]/',
            'email' => 'required|email|max:100',
           
        ]);

        // Update user 
        auth()->user()->update([

            'name' => $request->username,
            'email' => $request->email,

        ]);
        
        if ($request->notified) {
            auth()->user()->update([
                'notified' => 1
            ]);
        } 
        else {
            auth()->user()->update([
                'notified' => 0
            ]);
        }

        return redirect()->route('userProfile')->with('status','Your info are changed');

    }
}
