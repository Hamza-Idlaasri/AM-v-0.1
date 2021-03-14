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
        $this->middleware(['guest']);
    }

    public function index()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // validation
        $this->validate($request,[

            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|confirmed',

        ]);

        // Store User 
        User::create([

            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),

        ])->attachRole('superviseur');


        // Sign in 
        auth()->attempt($request->only('email', 'password'));

        return redirect()->route('overview');
    }
}
