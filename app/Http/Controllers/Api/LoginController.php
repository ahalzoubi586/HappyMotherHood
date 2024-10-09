<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GeneralSettings;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;

class LoginController extends BaseController
{
    public function getGeneralSettings()
    {
        try {
            $settings = GeneralSettings::first();
            Log::info($settings);
            return $this->sendResponse($settings);
        } catch (Exception $e) {
            return $this->sendError('error');
        }
    }
    public function register(Request $request)
    {
        Log::info($request->all());

        $validator = Validator::make(
            $request->all(),
            [
                'username' => ['required', 'unique:users,name'],
                'email' => ['required', 'email', 'unique:users,email'],
                'phone_number' => ['sometimes', 'numeric'],
                'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
                'password_confirmation' => ['required'],
            ],
            [
                'username.required' => 'حقل اسم المستخدم مطلوب',
                'username.unique' => 'اسم المستخدم مسجل مسبقاً',
                'email.required' => 'حقل البريد الإلكتروني مطلوب',
                'email.email' => 'البريد الإلكتروني غير صالح',
                'email.unique' => 'البريد الإلكتروني مسجل مسبقاً',
                'phone_number.numeric' => 'يجب أن يكون حقل رقم الهاتف رقمًا',
                'password.required' => 'حقل كلمة المرور مطلوب',
                'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
                'password.min' => 'الحد الأدنى لطول كلمة المرور هو 8 أحرف',
                'password.letters' => 'يجب أن تحتوي كلمة المرور على حرف واحد على الأقل',
                'password.numbers' => 'يجب أن تحتوي كلمة المرور على رقم واحد على الأقل',
                'password_confirmation.required' => 'حقل تأكيد كلمة المرور مطلوب',
            ],
        );

        if ($validator->fails()) {
            Log::info($validator->errors()->first());

            return $this->sendError($validator->errors()->first());
        } else {
            try {
                User::create([
                    'uid' =>$request->uid,
                    'name' => $request->username,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'password' => Hash::make($request->password),
                    'firebase_token' => $request->firebase_token,
                ]);
                Log::info('here1');
                return $this->sendResponse('تم التسجيل بنجاح');
            } catch (Exception $e) {
                Log::info('here2');
                return $this->sendError($e->getMessage());
            }
        }
    }
    public function login(Request $request)
    {
        try {
            $field = filter_var($request->get('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
            $username = $request->get('username');
            $password = $request->get('password');
            $admin[$field] = $username;
            $admin['password'] = $password;
            if (!Auth::attempt($admin)) {
                return $this->sendError('حصل خطأ أثناء تسجيل الدخول، يرجى المحاولة مرة أخرى');
            }
            $user = User::where($field, $username)->first();
            $user->firebase_token = $request->firebase_token;
            $user->save();
            $expiresAt = now()->addDay();
            $user->tokens()->delete();
            $auth_token = $user->createToken('auth-token' /*, expiresAt: $expiresAt*/)->plainTextToken;
            $info = [];
            $info['user_data'] = $user;
            $info['auth_token'] = $auth_token;
            $info['expiresAt'] = $expiresAt->timestamp * 1000;
            Log::info($info);
            return $this->sendResponse($info);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
    public function logout()
    {
        return $this->sendResponse('logout');
    }
}
