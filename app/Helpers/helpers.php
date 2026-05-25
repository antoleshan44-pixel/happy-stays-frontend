<?php

if (!function_exists('spring_boot_url')) {
    function spring_boot_url($path)
    {
        $baseUrl = env('SPRING_BOOT_BASE_URL', 'http://localhost:8080');
        $cleanPath = ltrim($path, '/');
        return $baseUrl . '/' . $cleanPath;
    }
}

if (!function_exists('prop')) {
    function prop($property, $key, $default = null)
    {
        if (is_object($property)) {
            return $property->$key ?? $default;
        }
        if (is_array($property)) {
            return $property[$key] ?? $default;
        }
        return $default;
    }
}
