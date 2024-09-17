<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'email', 'password', 'phone_number', 'duration', 'firebase_token'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function durations()
    {
        return $this->hasMany(Duration::class);
    }
    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
    public function unreadNotifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->whereNull('read_at'); // Adjust the condition based on your database schema
    }
    public function conversationsAsFirstUser()
    {
        return $this->hasMany(Conversation::class, 'first_user_id');
    }

    public function conversationsAsSecondUser()
    {
        return $this->hasMany(Conversation::class, 'second_user_id');
    }

    // Get all conversations for a specific user
    /*$user = User::find($userId);
$conversations = $user->conversationsAsFirstUser->merge($user->conversationsAsSecondUser);

// Get the users involved in a specific conversation
$conversation = Conversation::find($conversationId);
$firstUser = $conversation->firstUser;
$secondUser = $conversation->secondUser;*/
}
