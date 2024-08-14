<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public function user_1(){
        return $this->belongsTo(User::class,'first_user_id','id');
    }
    
    public function user_2(){
        return $this->belongsTo(User::class,'second_user_id','id');
    }
}
