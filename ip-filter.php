<?php

// Allowed countries
$allowedCountries = ['IT', 'MA'];

// Get visitor IP
$ip = $_SERVER['REMOTE_ADDR'] ?? '';

// Skip localhost
if ($ip === '127.0.0.1' || $ip === '::1') {
    return;
}

// Lookup country
$response = @file_get_contents(
    'http://ip-api.com/json/' . urlencode($ip) . '?fields=status,countryCode'
);

if ($response === false) {
    // If API fails, let visitor through
    return;
}

$data = json_decode($response, true);

if (
    !isset($data['status']) ||
    $data['status'] !== 'success'
) {
    return;
}

if (!in_array($data['countryCode'], $allowedCountries, true)) {
    header('Location: https://example.com/', true, 302);
    exit;
}