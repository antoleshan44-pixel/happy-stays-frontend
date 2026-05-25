<?php
// File: app/Helpers/CurrencyHelper.php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Format amount in Kenyan Shillings (KES)
     */
    public static function formatKES($amount, $withSymbol = true)
    {
        if ($withSymbol) {
            return 'KES ' . number_format($amount, 0);
        }
        return number_format($amount, 0);
    }

    /**
     * Convert USD to KES (approximate)
     */
    public static function usdToKes($usdAmount, $rate = 145)
    {
        return $usdAmount * $rate;
    }

    /**
     * Convert KES to USD
     */
    public static function kesToUsd($kesAmount, $rate = 145)
    {
        return $kesAmount / $rate;
    }

    /**
     * Calculate platform commission
     */
    public static function calculateCommission($amount, $propertyType = null)
    {
        $commissionRates = [
            'Villa' => 12,
            'Apartment' => 8,
            'House' => 10,
            'Cabin' => 10,
            'default' => 10
        ];

        $rate = $commissionRates[$propertyType] ?? $commissionRates['default'];
        return ($amount * $rate) / 100;
    }

    /**
     * Format amount with KES and handle null/zero values
     */
    public static function formatNullableKES($amount, $withSymbol = true)
    {
        if ($amount === null || $amount === '') {
            return $withSymbol ? 'KES 0' : '0';
        }
        return self::formatKES($amount, $withSymbol);
    }
}
