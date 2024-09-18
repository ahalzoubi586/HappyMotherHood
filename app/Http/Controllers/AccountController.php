<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;

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
            ],
        );

        // Start a database transaction
        DB::beginTransaction();

        try {
            $filePath = base_path('firebase_cred.json');

            if (!file_exists($filePath)) {
                return $this->sendError('Credential Not Valid');
            }

            if (!is_readable($filePath)) {
                return $this->sendError('Credential Unreadable');
            }

            $factory = (new Factory())->withServiceAccount($filePath)->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

            $auth = $factory->createAuth();
            $database = $factory->createDatabase();
            // Find the user by email
            $user = User::where('email', $request->email)->where('user_type',0)->firstOrFail();

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

            //Delete User Tokens
            $user->tokens()->delete();

            // Finally, delete the user
            $user->delete();

            // Commit the transaction
            $auth->deleteUser($user->uid);

            // Delete user data from Firebase Realtime Database
            $database->getReference('users/' . $user->uid)->remove();

            DB::commit();

            return back()->with('status', 'تم حذف حسابك بنجاح.');
        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollback();

            // Log the error for further investigation
            Log::error('Error deleting user: ' . $e->getMessage());

            return back()->withErrors(['error' => 'حدث خطأ أثناء حذف الحساب أو أن البريد الإلكتروني غير مرتبط بحساب مسبق.']);
        }
    }
}
