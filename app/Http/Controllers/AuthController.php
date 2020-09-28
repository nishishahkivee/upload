<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator,Redirect,Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use App\User;
class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }
    public function registration()
    {
        return view('registration');
    }
    public function postLogin(Request $request)
    {
        request()->validate([
            'email'=>'required',
            'password'=>'required',
        ]);
        $credentials=$request->only('email','password');
        if(Auth::attempt($credentials)){
            return redirect()->intended('dashboard');
        }
        else{
            echo ('please fill correct detail');
            return view('login');
        }
    }
    public function postRegistration(Request $request)
    {  
        request()->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        ]);
         
        $data = $request->all();
 
        $check = $this->create($data);
       
        return Redirect::to("dashboard")->withSuccess('Great! You have Successfully loggedin');
    }
     
    public function dashboard()
    {
 
      if(Auth::check()){
        return view('dashboard');
      }
       return Redirect::to("login")->withSuccess('Opps! You do not have access');
    }
 
    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }
     
    public function logout() {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }

}
