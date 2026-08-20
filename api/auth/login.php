<?php
session_start();
include_once "../dbconfig.php";
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: ' . (isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*'));
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$db   = new DBconfig();
$conn = $db->check_con();

if (!is_string($conn)) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $mail  = trim($_POST['mail-login']  ?? '');
        $passw = trim($_POST['passw-login'] ?? '');

        if (empty($mail) || empty($passw)) {
            echo json_encode(['result' => null]);
            exit();
        }

        $q = $conn->prepare("SELECT id FROM `users` WHERE email = ? AND passw = ?");
        $q->bind_param("ss", $mail, $passw);
        $q->execute();
        $getres = $q->get_result();

        if ($getres->num_rows > 0) {
            $datas = $getres->fetch_assoc();
            if (isset($datas['id'])) {
                $_SESSION['id'] = $datas['id'];
                $clientIP = getClientRealIP();
                $db->upload_data('IPADDR', $clientIP, $datas['id']);
                echo json_encode(['result' => $datas['id']]);
            } else {
                echo json_encode(['result' => 'Try again']);
            }
        } else {
            echo json_encode(['result' => null]);
        }
    } else {
        echo json_encode(['result' => null]);
    }
} else {
    echo json_encode(['result' => null]);
}

function getClientRealIP() {
    $headers = [
        'HTTP_CF_CONNECTING_IP',
        'HTTP_X_REAL_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_CLIENT_IP',
        'REMOTE_ADDR',
    ];
    foreach ($headers as $header) {
        if (!empty($_SERVER[$header])) {
            $ip = trim(explode(',', $_SERVER[$header])[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return $ip;
            }
        }
    }
    return $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
}
?>