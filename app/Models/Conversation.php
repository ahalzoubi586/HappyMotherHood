<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Conversation extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_user_id',
        'second_user_id',
        'last_message',
        'last_message_time',
        'last_message_read'
    ];
    public function user_1()
    {
        return $this->belongsTo(User::class, 'first_user_id', 'id');
    }

    public function user_2()
    {
        return $this->belongsTo(User::class, 'second_user_id', 'id');
    }
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    // Get the last message in the conversation
    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latest('created_at');
    }
}
