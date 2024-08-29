<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    public $timestamps = true;
    use HasFactory;
    protected $fillable = ['type', 'notifiable_type', 'notifiable_id', 'data', 'read_at'];

    protected $dates = ['read_at', 'created_at', 'updated_at'];
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
