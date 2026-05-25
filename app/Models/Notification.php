<?php
// File: app/Models/Notification.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'user_id', 'type', 'title', 'message', 'data', 'is_read',
        'is_admin_notification', 'read_at', 'created_at'
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'is_admin_notification' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public $timestamps = false;
}
