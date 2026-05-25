<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/mpesa/callback',           // M-Pesa callback endpoint
        '/payment/mpesa/*/process',  // Payment processing routes (if needed)
        '/webhook/*',                // Any future webhooks
    ];
}