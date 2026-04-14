<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap/app.php';

require dirname(__DIR__) . '/PHPMailer/Exception.php';
require dirname(__DIR__) . '/PHPMailer/PHPMailer.php';
require dirname(__DIR__) . '/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim((string) ($_POST['name'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $phone = trim((string) ($_POST['phone'] ?? ''));
    $message = trim((string) ($_POST['message'] ?? ''));
    $website = trim((string) ($_POST['website'] ?? ''));

    // Honeypot: real users won't fill this hidden field; bots often do.
    if ($website !== '') {
        redirect('contact?sent=1');
    }

    if ($name === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect('contact?sent=0');
    }

    $blockKeywords = config('contact_block_keywords', []);
    $allowKeywords = config('contact_allow_keywords', []);
    $combinedText = mb_strtolower($name . ' ' . $email . ' ' . $message, 'UTF-8');

    $allowMatched = false;
    if (is_array($allowKeywords)) {
        foreach ($allowKeywords as $keyword) {
            $needle = mb_strtolower(trim((string) $keyword), 'UTF-8');
            if ($needle !== '' && str_contains($combinedText, $needle)) {
                $allowMatched = true;
                break;
            }
        }
    }

    $keywordMatched = false;
    if (is_array($blockKeywords)) {
        foreach ($blockKeywords as $keyword) {
            $needle = mb_strtolower(trim((string) $keyword), 'UTF-8');
            if ($needle !== '' && str_contains($combinedText, $needle)) {
                $keywordMatched = true;
                break;
            }
        }
    }

    // Treat marketing-like messages with links as advertiser spam.
    $urlCount = preg_match_all('/(?:https?:\/\/|www\.)/i', $message);
    if (! $allowMatched && ($keywordMatched || $urlCount >= 2)) {
        error_log('Contact form blocked as advertiser spam for email: ' . $email);
        redirect('contact?sent=1');
    }

    $smtpHost = trim((string) config('smtp_host', ''));
    $smtpPort = (int) config('smtp_port', 587);
    $smtpUsername = trim((string) config('smtp_username', ''));
    $smtpPassword = (string) config('smtp_password', '');
    $smtpEncryption = strtolower(trim((string) config('smtp_encryption', 'tls')));

    $fromEmail = trim((string) config('smtp_from_email', $smtpUsername));
    $fromName = trim((string) config('smtp_from_name', (string) config('app_name', 'Mario Motors')));
    $toEmail = trim((string) config('app_email', ''));

    if ($smtpHost === '' || $smtpPort <= 0 || $smtpUsername === '' || $smtpPassword === '' || $fromEmail === '' || $toEmail === '') {
        error_log('Contact form SMTP is not configured.');
        redirect('contact?sent=0');
    }

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $smtpHost;
        $mail->SMTPAuth = true;
        $mail->Username = $smtpUsername;
        $mail->Password = $smtpPassword;
        $mail->Port = $smtpPort;

        if ($smtpEncryption === 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } elseif ($smtpEncryption === 'none' || $smtpEncryption === '') {
            $mail->SMTPSecure = '';
            $mail->SMTPAutoTLS = false;
        } else {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }

        $mail->CharSet = 'UTF-8';
        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($toEmail);
        $mail->addReplyTo($email, $name);

        $subject = 'New contact enquiry from website';
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $safeEmail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
        $safePhone = htmlspecialchars($phone === '' ? 'Not provided' : $phone, ENT_QUOTES, 'UTF-8');
        $safeMessage = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body =
            '<h2>New Contact Enquiry</h2>' .
            '<p><strong>Name:</strong> ' . $safeName . '</p>' .
            '<p><strong>Email:</strong> ' . $safeEmail . '</p>' .
            '<p><strong>Phone:</strong> ' . $safePhone . '</p>' .
            '<p><strong>Message:</strong><br>' . $safeMessage . '</p>';

        $mail->AltBody =
            "New Contact Enquiry\n\n" .
            'Name: ' . $name . "\n" .
            'Email: ' . $email . "\n" .
            'Phone: ' . ($phone === '' ? 'Not provided' : $phone) . "\n\n" .
            "Message:\n" . $message;

        $mail->send();
        redirect('contact?sent=1');
    } catch (Throwable $e) {
        error_log('Contact form mail error: ' . $e->getMessage());
        redirect('contact?sent=0');
    }
}

$sentParam = $_GET['sent'] ?? null;
$sent = $sentParam === '1';
$failed = $sentParam === '0';

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

layout_start(compact('pageTitle', 'currentNav', 'sent', 'failed', 'info', 'metaDescription', 'waUrl'));
require VIEW_PATH . '/pages/contact.php';
layout_end();
