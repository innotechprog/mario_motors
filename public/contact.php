<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    redirect('contact?sent=1');
}

$sent = isset($_GET['sent']);

$pageTitle = 'Contact';
$currentNav = 'contact';
$metaDescription = 'Contact Mario Motors & Spare Parts in Mayville, Pretoria. Call 012 304 0937 or 062 397 4149, WhatsApp, or email — we reply as soon as we can.';

$wa = (string) config('whatsapp_number');
$waUrl = $wa !== '' ? 'https://wa.me/' . preg_replace('/\D/', '', $wa) : '';
$addrPlain = trim(str_replace(["\r\n", "\n", "\r"], ', ', business_address_display()));
$mapsUrl = $addrPlain !== '' ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($addrPlain) : '';

$info = [
    [
        'label' => 'Landline',
        'value' => (string) config('business_phone_display'),
        'link_url' => 'tel:' . preg_replace('/[^\d+]/', '', (string) config('business_phone')),
        'icon' => '',
    ],
    [
        'label' => 'Mobile',
        'value' => (string) config('business_mobile_display'),
        'link_url' => 'tel:' . preg_replace('/[^\d+]/', '', (string) config('business_mobile')),
        'icon' => '',
    ],
];

if ($waUrl !== '') {
    $info[] = [
        'label' => 'WhatsApp',
        'value' => 'Chat on WhatsApp',
        'link_url' => $waUrl,
        'external' => true,
        'icon' => '',
    ];
}

$info[] = [
    'label' => 'Email',
    'value' => (string) config('app_email'),
    'link_url' => 'mailto:' . (string) config('app_email'),
    'icon' => '',
];

$info[] = [
    'label' => 'Address',
    'value' => business_address_display(),
    'maps_url' => $mapsUrl !== '' ? $mapsUrl : null,
    'icon' => '',
];

$info[] = [
    'label' => 'Hours',
    'value' => business_hours_text(),
    'icon' => '',
];

layout_start(compact('pageTitle', 'currentNav', 'sent', 'info', 'metaDescription', 'waUrl'));
require VIEW_PATH . '/pages/contact.php';
layout_end();
