<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    
    public function __construct()
    {
        $this->middleware(['agent']);
    }

    public function index()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // validation
        $this->validate($request,[

            'name' => 'required|min:3|max:15|unique:mysql2.users|regex:/^[a-zA-Z][a-zA-Z0-9-_(). ÀÂÇÉÈÊÎÔÛÙàâçéèêôûù]/',
            'email' => 'required|email|max:100|unique:mysql2.users',
            'password' => 'required|string|confirmed|min:5|max:12|regex:/^[a-zA-Z0-9-_().@$=%&#+{}*ÀÂÇÉÈÊÎÔÛÙàâçéèêôûù]/|unique:mysql2.users',

        ]);

        // Store User 
        User::create([

            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),

        ])->attachRole('superviseur');


        // Sign in 
        // auth()->attempt($request->only('email', 'password'));

        // return redirect()->route('overview');

        return redirect()->route('config.users');
    }
}
