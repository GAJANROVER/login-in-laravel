<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthManager extends Controller
{
    function login()
    {
        if (Auth::check()) {
            return redirect()->intended(route('home'));
        }
        return view('login');
    }

    function registration()
    {
        if (Auth::check()) {
            return redirect()->intended(route('home'));
        }
        return view('registration');
    }

    function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended(route('home'))->with('success', 'Login Successfully');
        }

        return redirect(route('login'))->with('error', 'Check the Credentials');
    }

    function registrationPost(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);

        if (!$user) {
            return redirect(route('registration'))->with('error', 'Registration Failed');
        }

        return redirect(route('login'))->with('success', 'Registration Success');
    }

    function logout()
    {
        Auth::logout();
        return redirect(route('login'));
    }
}
