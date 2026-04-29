<?php
// config/mailer.php

/**
 * Mengirim email menggunakan Brevo (Sendinblue) API v3
 */
function smtp_send_mail($config, $to, $subject, $body, &$error = null) {
    $apiKey = $config['brevo_api_key'] ?? '';
    $fromName = $config['from_name'] ?? 'Admin';
    $fromEmail = $config['from_email'] ?? '';

    if (empty($apiKey) || $apiKey === 'YOUR_BREVO_API_KEY_HERE') {
        $error = 'API Key Brevo belum dikonfigurasi di config/mail_config.php';
        return false;
    }

    $data = [
        'sender' => ['name' => $fromName, 'email' => $fromEmail],
        'to' => [['email' => $to]],
        'subject' => $subject,
        'textContent' => $body
    ];

    $ch = curl_init('https://api.brevo.com/v3/smtp/email');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'accept: application/json',
        'api-key: ' . $apiKey,
        'content-type: application/json'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        $error = "Curl Error: " . $curlError;
        return false;
    }

    $resData = json_decode($response, true);
    if ($httpCode >= 200 && $httpCode < 300) {
        return true;
    } else {
        $error = "Brevo Error ({$httpCode}): " . ($resData['message'] ?? $response);
        return false;
    }
}
