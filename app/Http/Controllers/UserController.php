<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //show register form 
    public function create(){
        return view('users.register');
    }

    public function store(Request $request){
        $formField = $request->validate([
            'name'=>['required', 'min:3'],
            'email'=>['required', 'email', Rule::unique('users', 'email')],
            'password'=>'required|confirmed|min:6'
        ]);
        //hash password
        $formField['password'] = bcrypt($formField['password']);
        $user = User::create($formField);

        //login
        auth()->login($user);
        return redirect('/')->with('message', 'User created and logged in');
    }


    public function logout(Request $request){
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('message', 'you have been logged out');

    }


    public function login(){
        return view('users.login');
    }

    public function authenticate(Request $request){

        $formField = $request->validate([
            
            'email'=>['required', 'email'],
            'password'=>'required'
        ]);
        if(auth()->attempt($formField)){
            $request->session()->regenerate();
            return redirect('/')->with('message', 'You are new logged in!');

        }
        return back()->withErrors(['email'=>'Invalid Credentials'])->onlyInput('email');

    }
    

}


