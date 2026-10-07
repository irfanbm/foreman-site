<?php
// Foreman waitlist endpoint.
// Upload alongside index.html, then set FORM_ENDPOINT = "subscribe.php" in index.html.
// Fill in the two constants below. Everything else works as-is.

// 1) Telegram bot token (same bot you use for cron reports)
define('TELEGRAM_BOT_TOKEN', 'PASTE_BOT_TOKEN_HERE');
// 2) Chat ID that should receive the signup alert (group ID, usually negative)
define('TELEGRAM_CHAT_ID', 'PASTE_CHAT_ID_HERE');
// Set false if you only want the CSV without Telegram alerts
define('NOTIFY_TELEGRAM', true);
// Where signups are stored (same folder; download it periodically)
define('CSV_FILE', __DIR__ . '/waitlist.csv');

header('Content-Type: application/json');

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
$email = strtolower(trim($data['email'] ?? ''));

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => 'invalid email']);
    exit;
}

// dedup: ignore if already on the list
$exists = false;
if (file_exists(CSV_FILE)) {
    $lines = file(CSV_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines) {
        foreach ($lines as $line) {
            $row = str_getcsv($line);
            if (strcasecmp($row[0] ?? '', $email) === 0) { $exists = true; break; }
        }
    }
}

if (!$exists) {
    $fp = fopen(CSV_FILE, 'a');
    if ($fp) {
        fputcsv($fp, [$email, gmdate('Y-m-d H:i:s')]);
        fclose($fp);
    }
    if (NOTIFY_TELEGRAM && TELEGRAM_BOT_TOKEN !== 'PASTE_BOT_TOKEN_HERE') {
        $ch = curl_init('https://api.telegram.org/bot' . TELEGRAM_BOT_TOKEN . '/sendMessage');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_POSTFIELDS => [
                'chat_id' => TELEGRAM_CHAT_ID,
                'text' => "Foreman waitlist: new signup\n" . $email,
            ],
        ]);
        curl_exec($ch);
        curl_close($ch);
    }
}

echo json_encode(['ok' => true]);
