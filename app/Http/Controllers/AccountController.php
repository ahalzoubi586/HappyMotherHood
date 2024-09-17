<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    public function showDeleteAccountForm()
    {
        return view('Pages.General.delete_account');
    }

    // Handle the account deletion request
    public function deleteAccount(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required',
            ],
            [
                'email.required' => 'حقل البريد الإلكتروني مطلوب',
                'email.email' => 'يجب أن يكون حقل البريد الإلكتروني صحيحاً',
                'password.required' => 'حقل كلمة المرور مطلوب',
            ]
        );
        
        // Start a database transaction
        DB::beginTransaction();
        
        try {
            // Find the user by email
            $user = User::where('email', $request->email)->firstOrFail();
        
            // Check if the password is correct
            if (!Hash::check($request->password, $user->password)) {
                return back()
                    ->withInput()
                    ->withErrors(['password' => 'كلمة المرور غير صحيحة.']);
            }
        
            // Delete all messages in conversations where the user is the first or second user
            $user->conversationsAsFirstUser()->each(function ($conversation) {
                $conversation->messages()->delete();
            });
            $user->conversationsAsSecondUser()->each(function ($conversation) {
                $conversation->messages()->delete();
            });
        
            // Now delete the conversations
            $user->conversationsAsFirstUser()->delete();
            $user->conversationsAsSecondUser()->delete();
        
            // Delete user notifications
            $user->notifications()->delete();
        
            // Delete user durations
            $user->durations()->delete();
        
            // Finally, delete the user
            $user->delete();
        
            // Commit the transaction
            DB::commit();
        
            return back()->with('status', 'تم حذف حسابك بنجاح.');
        
        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollback();
        
            // Log the error for further investigation
            Log::error('Error deleting user: ' . $e->getMessage());
        
            return back()->withErrors(['error' => 'حدث خطأ أثناء حذف الحساب.']);
        }
    }
}
