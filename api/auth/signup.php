<?php
include_once "../dbconfig.php";
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: ' . (isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*'));
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['result' => 500]);
    exit;
}

$dbconf = new DBconfig();
$con    = $dbconf->check_con();

$name   = trim($_POST['student-name'] ?? '');
$gender = trim($_POST['gender']       ?? '');
$phone  = trim($_POST['phone']        ?? '');
$email  = trim($_POST['email']        ?? '');
$passw  = $_POST['passw']             ?? '';

if (!$name || !$gender || !$phone || !$email || !$passw) {
    echo json_encode(['result' => 422]);
    exit;
}

$check = $con->prepare("SELECT id FROM users WHERE email=? OR phone=? LIMIT 1");
$check->bind_param("ss", $email, $phone);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(['result' => 409]);
    exit;
}

$id = random_int(100000, 999999);
$ip = getClientRealIP();

$con->begin_transaction();

try {
    $q1 = $con->prepare(
        "INSERT INTO users (id,name,gender,phone,email,passw,IPADDR) VALUES (?,?,?,?,?,?,?)"
    );
    $q1->bind_param("sssssss", $id, $name, $gender, $phone, $email, $passw, $ip);
    $q1->execute();

    $q2 = $con->prepare("INSERT INTO course (id) VALUES (?)");
    $q2->bind_param("s", $id);
    $q2->execute();

    $con->commit();
    echo json_encode(['result' => 200]);

} catch (Exception $e) {
    $con->rollback();
    echo json_encode(['result' => 500]);
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
