<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['last_message_at'];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(PrivateMessage::class);
    }

    public function lastMessage()
    {
        return $this->hasOne(PrivateMessage::class)->latestOfMany();
    }
}
