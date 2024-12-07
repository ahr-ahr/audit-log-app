<?php
require 'db.php';
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

define('TELEGRAM_BOT_TOKEN', '6829666174:AAHaBjqjBg_lwteK4O-NpLiMzbckGi5Szuw');
define('TELEGRAM_CHAT_ID', '6680224117');

function fetchRecentLogs($conn) {
    $result = $conn->query("SELECT * FROM audit_logs ORDER BY timestamp DESC LIMIT 10");
    $logs = [];
    while ($row = $result->fetch_assoc()) {
        $logs[] = $row;
    }
    return $logs;
}

function formatLogs($logs) {
    $formatted = "Recent Audit Logs:\n\n";
    foreach ($logs as $log) {
        $formatted .= "ID: {$log['id']}\n";
        $formatted .= "IP: {$log['ip_address']}\n";
        $formatted .= "User ID: {$log['user_id']}\n";
        $formatted .= "Action: {$log['action']}\n";
        $formatted .= "Method: {$log['method']}\n";
        $formatted .= "URI: {$log['request_uri']}\n";
        $formatted .= "Timestamp: {$log['timestamp']}\n\n";
    }
    return $formatted;
}

function sendWhatsApp($message, $to) {
    $url = "http://localhost:3000/api/whatsapp";
    $data = [
        "platform" => "whatsapp",
        "operation" => "send-text",
        "session" => "session1",
        "payload" => [
            "to" => $to,
            "message" => $message
        ]
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
        ],
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        echo "Failed to send message to WhatsApp.\n";
    } else {
        echo "Message sent to WhatsApp successfully.\n";
    }
}

function sendEmail($logs, $recipientEmail) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.zoho.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ahr@ahr180607.my.id';
        $mail->Password = 'Haikal180607';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('ahr@ahr180607.my.id', 'Audit Log Sender');
        $mail->addAddress($recipientEmail);

        $mail->isHTML(true);
        $mail->Subject = 'Recent Audit Logs';
        $mail->Body = nl2br($logs);

        $mail->send();
        echo "Email sent successfully.\n";
    } catch (Exception $e) {
        echo "Email failed to send. Error: {$mail->ErrorInfo}\n";
    }
}

function sendTelegram($message) {
    $url = "https://api.telegram.org/bot" . TELEGRAM_BOT_TOKEN . "/sendMessage";
    $data = [
        'chat_id' => TELEGRAM_CHAT_ID,
        'text' => $message,
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) {
        echo "Failed to send message to Telegram.\n";
    } else {
        echo "Message sent to Telegram successfully.\n";
    }
}

$logs = fetchRecentLogs($conn);
if (!empty($logs)) {
    $formattedLogs = formatLogs($logs);

    $recipientWhatsApp = "6282331422421";
    sendWhatsApp($formattedLogs, $recipientWhatsApp);

    sendEmail($formattedLogs, 'ahr2396@gmail.com');

    sendTelegram($formattedLogs);
} else {
    echo "No logs to send.\n";
}
?>