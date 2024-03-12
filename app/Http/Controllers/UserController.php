<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function registration()
    {
        return view('auth.register');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function registerUser(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|numeric|min:11',
            'password' => 'required|string|min:8',
        ]);

        // $user = User::create([
        //     'credential_for' => $request->credential_for,
        //     'email' => $request->email,
        //     'url' => $request->url,
        //     'ip_address' => $request->ip_address,
        //     'username' => $request->username,
        //     'password' => Hash::make($request->password),
        // ]);

        $user = new User();
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->password = Hash::make($request->password);
        $result = $user->save();

        if ($result) {
            return back()->with('success', 'Registered Successfully');
        } else {
            return back()->with('fail', 'Something wrong');
        }        
        
    }

    public function loginUser(Request $request) {
        $request->validate([
            'email' => 'required|string|email|max:255|',
            'password' => 'required|string|min:8',
        ]);

        $user = User::where('email', '=', $request->email)->first();
        if($user) {
            if(Hash::check($request->password, $user->password)){
                $request->session()->put('loginId', $user->id);
                return redirect('firstPage');   
            } else {
                return back()->with('fail', 'Password not matched');
            }
        } else {
            return back()->with('fail', 'The email is not recognised');
        }
    }
    public function firstPage(){
        return view('firstPage');
    }

    public function pagesUserProfile(){
        return view('pages-user-profile');
    }
}
