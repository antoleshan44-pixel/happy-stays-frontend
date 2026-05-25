<?php
// File: app/Models/BlacklistedIp.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlacklistedIp extends Model
{
    protected $table = 'blacklisted_ips';

    protected $fillable = [
        'ip_address', 'reason', 'reported_by', 'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public $timestamps = false;
}
