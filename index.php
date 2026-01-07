<?php

$BOT_TOKEN = "8405432251:AAGFsqc2hmo_Y-yc-V2eMTXVPCRct9x6UAE";

/**
 * 👈 خیلی مهم
 * اینو بعد از اولین پیام، از لاگ تلگرام درمیاریم
 * فعلاً خالی میذاریم
 */
$ADMIN_CHAT_ID = null;

// دریافت ورودی تلگرام
$input = file_get_contents("php://input");
$update = json_decode($input, true);

// اگر هیچ دیتایی نیومده
if (!$update) {
    exit;
}

// گرفتن chat_id
$chat_id = $update['message']['chat']['id'] ?? null;
$text    = trim($update['message']['text'] ?? '');

// اگر اولین پیام بود → admin رو ست کن
if ($ADMIN_CHAT_ID === null && $chat_id) {
    sendMessage($chat_id, "✅ ربات زنده است\nChat ID شما:\n$chat_id");
}

// دیباگ: ارسال کل payload به تلگرام
sendMessage($chat_id, "📦 DEBUG PAYLOAD:\n" . json_encode($update, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// پاسخ به /start
if ($text === '/start') {
    sendMessage($chat_id, "سلام 👋\nربات حضور و غیاب تافته فعال شد ✅");
}

// ------------------------

function sendMessage($chat_id, $text)
{
    global $BOT_TOKEN;

    $url = "https://api.telegram.org/bot$BOT_TOKEN/sendMessage";

    $data = [
        'chat_id' => $chat_id,
        'text'    => $text
    ];

    $options = [
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($data),
            'timeout' => 10
        ]
    ];

    file_get_contents($url, false, stream_context_create($options));
}
