<?php
// File: app/Http/Controllers/PriceAlertController.php

namespace App\Http\Controllers;

use App\Models\PriceAlert;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PriceAlertController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'target_price' => 'required|numeric|min:0'
        ]);
        
        PriceAlert::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'property_id' => $request->property_id
            ],
            [
                'target_price' => $request->target_price,
                'is_active' => true,
                'notified_at' => null
            ]
        );
        
        return back()->with('success', 'Price alert set successfully!');
    }
    
    public function checkPriceAlerts()
    {
        $alerts = PriceAlert::where('is_active', true)
            ->whereNull('notified_at')
            ->with(['property', 'user'])
            ->get();
        
        foreach ($alerts as $alert) {
            if ($alert->property->price_per_night <= $alert->target_price) {
                // Send notification
                Mail::raw("Great news! The property '{$alert->property->title}' has dropped to KES {$alert->property->price_per_night} which is below your target of KES {$alert->target_price}.", function($mail) use ($alert) {
                    $mail->to($alert->user->email)
                         ->subject('Price Alert - Eserian Homes');
                });
                
                $alert->update(['notified_at' => now()]);
            }
        }
    }
}