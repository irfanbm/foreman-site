<?php
// Foreman waitlist endpoint.
// Upload alongside index.html. FORM_ENDPOINT in index.html points here.
//
// Telegram alerts need credentials from subscribe-config.php (same folder,
// one-time manual upload via File Manager — never committed to git):
//   define('WL_BOT_TOKEN', '...');
//   define('WL_CHAT_ID', '...');
// Without it, signups are still saved to CSV, just without Telegram alerts.

define('CSV_FILE', __DIR__ . '/waitlist.csv');
$config = __DIR__ . '/subscribe-config.php';
if (file_exists($config)) {
    include $config;
}

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
        // lock so concurrent signups can't corrupt the file
        if (flock($fp, LOCK_EX)) {
            fputcsv($fp, [$email, gmdate('Y-m-d H:i:s')]);
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }
    if (defined('WL_BOT_TOKEN') && defined('WL_CHAT_ID')) {
        $ch = curl_init('https://api.telegram.org/bot' . WL_BOT_TOKEN . '/sendMessage');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_POSTFIELDS => [
                'chat_id' => WL_CHAT_ID,
                'text' => "[Foreman] new waitlist signup\n" . $email,
            ],
        ]);
        curl_exec($ch);
        curl_close($ch);
    }
}

echo json_encode(['ok' => true]);
