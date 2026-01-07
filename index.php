<?php
// index.php
// ربات ساده تلگرام برای Webhook
// پاسخ /start و آماده توسعه

// توکن ربات خودت رو اینجا بگذار
$BOT_TOKEN = "8405432251:AAGFsqc2hmo_Y-yc-V2eMTXVPCRct9x6UAE";

// خواندن داده‌های ورودی (پیام تلگرام)
$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update) {
    // اگر چیزی نبود، خارج شو
    exit;
}

// گرفتن chat_id و متن پیام
$chat_id = $update['message']['chat']['id'] ?? null;
$text = trim($update['message']['text'] ?? '');

// بررسی دستورات
if ($text === '/start') {
    sendMessage($chat_id, "سلام 👋\nربات حضور و غیاب تافته فعال شد ✅");
}

// تابع ارسال پیام
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
    file_get_contents($url, false, $context);
}
