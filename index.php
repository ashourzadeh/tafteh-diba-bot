<?php
// index.php
// ربات تلگرام با دیباگ ساده
// لاگ پیام‌ها و پاسخ به کاربر

$BOT_TOKEN = "8405432251:AAGFsqc2hmo_Y-yc-V2eMTXVPCRct9x6UAE";

// مسیر فایل لاگ روی RailWay
$log_file = __DIR__ . "/bot_debug.log";

// گرفتن ورودی تلگرام
$content = file_get_contents("php://input");
$update = json_decode($content, true);

// لاگ کردن ورودی
file_put_contents($log_file, date("Y-m-d H:i:s") . " INPUT: " . print_r($update, true) . "\n", FILE_APPEND);

if (!$update) {
    exit;
}

// گرفتن chat_id و متن پیام
$chat_id = $update['message']['chat']['id'] ?? null;
$text = trim($update['message']['text'] ?? '');

// بررسی دستورها
if ($text === '/start') {
    sendMessage($chat_id, "سلام 👋\nربات حضور و غیاب تافته فعال شد ✅");
}

// تابع ارسال پیام با لاگ
function sendMessage($chat_id, $text)
{
    global $BOT_TOKEN, $log_file;
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
    $result = file_get_contents($url, false, $context);

    // لاگ جواب تلگرام
    file_put_contents($log_file, date("Y-m-d H:i:s") . " RESPONSE: " . $result . "\n", FILE_APPEND);
}
