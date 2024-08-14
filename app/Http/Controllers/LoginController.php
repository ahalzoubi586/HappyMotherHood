<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index()
    {
        return view("Pages.Auth.login");
    }
    public function login(Request $request)
    {
        $save_data = $request->all();
        $validator = Validator::make([
            'email' => $save_data['email'],
            'password' => $save_data['password'],
        ], [
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email' => [
                'required' =>"حقل البريد الإلكتروني مطلوب",
                'email' => 'الرجاء إدخال بريد إلكتروني صحيح'
            ],
            'password' =>[
                'required' =>'حقل كلمة المرور مطلوب'
            ]
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $admin['email'] = $request->email;
        $admin['password'] = $request->password;
        if (Auth::attempt($admin, false)) {
            return redirect()->route("dashboard.index");
        }else{
            return redirect()->back()->withErrors(['الرجاء التأكد من معلومات الدخول'])->withInput();
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('index');
    }
}
