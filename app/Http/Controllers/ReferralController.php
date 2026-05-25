<?php
// File: app/Http/Controllers/ReferralController.php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\ReferralCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReferralController extends Controller
{
    public function index()
    {
        $referralCode = ReferralCode::firstOrCreate(
            ['user_id' => Auth::id()],
            ['code' => Str::upper(Str::random(8))]
        );
        
        $referrals = Referral::where('referrer_id', Auth::id())->with('referred')->get();
        $totalEarned = $referrals->where('status', 'completed')->sum('reward_amount');
        
        return view('referrals.index', compact('referralCode', 'referrals', 'totalEarned'));
    }
    
    public function redeem($code)
    {
        $referralCode = ReferralCode::where('code', $code)->first();
        
        if (!$referralCode || $referralCode->user_id === Auth::id()) {
            return redirect()->route('register')->with('error', 'Invalid referral code');
        }
        
        session(['referral_code' => $code]);
        return redirect()->route('register');
    }
    
    public function completeReferral($userId)
    {
        $referralCode = ReferralCode::where('code', session('referral_code'))->first();
        
        if ($referralCode) {
            Referral::create([
                'referrer_id' => $referralCode->user_id,
                'referred_id' => $userId,
                'code' => $referralCode->code,
                'reward_amount' => 1000, // KES 1000 reward
                'status' => 'completed'
            ]);
            
            // Add credits to referrer (implement credits system)
        }
        
        session()->forget('referral_code');
    }
}