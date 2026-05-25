<?php
// File: app/Models/AdminLoginHistory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLoginHistory extends Model
{
    protected $table = 'admin_login_history';

    protected $fillable = [
        'admin_id', 'ip_address', 'user_agent', 'device_fingerprint',
        'location', 'status', 'login_method', 'created_at'
    ];

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
