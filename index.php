<?php
// index.php
// ربات تلگرام موقت با دیباگ کامل
// پاسخ /start و لاگ payload ورودی

$BOT_TOKEN = "8405432251:AAGFsqc2hmo_Y-yc-V2eMTXVPCRct9x6UAE";

// خواندن داده‌های ورودی Telegram
$content = file_get_contents("php://input");
$update = json_decode($content, true);

// لاگ ورودی به Logs Railway
error_log("==== TELEGRAM UPDATE ====");
error_log(print_r($update, true));
error_log("========================");

if (!$update) {
    exit;
}

// گرفتن chat_id و متن پیام
$chat_id = $update['message']['chat']['id'] ?? null;
$text = trim($update['message']['text'] ?? '');

// بررسی دستورها
if ($text === '/start') {
    sendMessage($chat_id, "سلام 👋\nربات حضور و غیاب تافته فعال شد ✅");

    // ارسال debug پیام به خود کاربر برای بررسی payload
    sendMessage($chat_id, "DEBUG: Payload دریافت شد و تو Logs ثبت شد.");
}

// تابع ارسال پیام با لاگ
function sendMessage($chat_id, $text)
{
    global $BOT_TOKEN;

    $url = "https://api.telegram.org/bot$BOT_TOKEN/sendMessage";

    $data = [
        'chat_id' => $chat_id,
        'text' => $text
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
            'timeout' => 10
        ]
    ];

    $context  = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    // لاگ پاسخ تلگرام
    error_log("==== TELEGRAM RESPONSE ====");
    error_log($result);
    error_log("===========================");
}
