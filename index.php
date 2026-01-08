<?php
// ================= HEALTH CHECK =================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    http_response_code(200);
    echo "OK";
    exit;
}
// =================================================


// ================== CONFIG ==================
$BOT_TOKEN = "8405432251:AAGFsqc2hmo_Y-yc-V2eMTXVPCRct9x6UAE";
$API_URL   = "https://api.telegram.org/bot".$BOT_TOKEN;
$USER_API  = "http://2.187.18.231:2215/api/get_user.php?code=";
// ============================================


// فقط POST (وبهوک تلگرام)
$update = json_decode(file_get_contents("php://input"), true);
if (!$update) exit;

$message  = $update['message'] ?? null;
$chat_id = $message['chat']['id'] ?? null;
$text    = trim($message['text'] ?? '');

if (!$chat_id) exit;


// ================= FUNCTIONS =================
function sendMessage($chat_id, $text)
{
    global $API_URL;

    file_get_contents(
        $API_URL . "/sendMessage?" . http_build_query([
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode' => 'HTML'
        ])
    );
}

function getUserInfo($code)
{
    global $USER_API;

    $response = @file_get_contents($USER_API . urlencode($code));
    if ($response === false) {
        return false;
    }

    $json = json_decode($response, true);
    if (!$json || $json['ok'] !== true) {
        return false;
    }

    return trim($json['fname']);
}

// =============================================


// ================= BOT LOGIC =================
if ($text === "/start") {
    sendMessage($chat_id,
        "سلام 👋\n\n".
        "به <b>ربات حضور و غیاب تافته</b> خوش آمدید ✅\n\n".
        "لطفاً <b>کد پرسنلی</b> خود را وارد کنید:"
    );
    exit;
}

if (preg_match('/^\d+$/', $text)) {

    sendMessage($chat_id, "⏳ در حال بررسی اطلاعات...");

    $fname = getUserInfo($text);

    if ($fname) {
        sendMessage($chat_id, "✅ خوش آمدید <b>$fname</b>");
    } else {
        sendMessage($chat_id, "❌ کد پرسنلی نامعتبر است.");
        // sendMessage($chat_id, "DEBUG API:\n".$response);
    }
    exit;
}

sendMessage($chat_id, "⚠️ لطفاً فقط کد پرسنلی عددی ارسال کنید.");
