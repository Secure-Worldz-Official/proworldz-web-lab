<?php
// TEMPORARY DIAGNOSTIC — DELETE THIS FILE AFTER USE
session_start();

if (empty($_SESSION['admin_username'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Not logged in as admin']);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../api/dbconfig.php';
$db = new DBconfig();

$out = [];

// 1. DB connection check
if (!$db->con || $db->con->connect_error) {
    $out['db_connect'] = 'FAILED: ' . ($db->con->connect_error ?? 'no connection object');
} else {
    $out['db_connect'] = 'OK';
}

// 2. Check users table columns
$colRes = $db->con->query("SHOW COLUMNS FROM users");
$cols = [];
if ($colRes) {
    while ($row = $colRes->fetch_assoc()) {
        $cols[] = $row['Field'];
    }
}
$out['users_columns'] = $cols;
$out['users_has_course_col'] = in_array('course', $cols) ? 'YES' : 'NO';

// 3. Check payment_verifications table
$pvCheck = $db->con->query("SHOW TABLES LIKE 'payment_verifications'");
$out['payment_verifications_table_exists'] = ($pvCheck && $pvCheck->num_rows > 0) ? 'YES' : 'NO';

// 4. Run ensurePaymentVerificationsTable
$db->ensurePaymentVerificationsTable();
$pvCheck2 = $db->con->query("SHOW TABLES LIKE 'payment_verifications'");
$out['payment_verifications_table_after_ensure'] = ($pvCheck2 && $pvCheck2->num_rows > 0) ? 'YES' : 'NO';

// 5. Run the exact SELECT query from api_owasp2026_requests.php
$sql = "SELECT pv.id, pv.user_id, pv.screenshot_path, pv.payment_method, pv.status, pv.created_at, pv.reviewed_at,
               u.name AS student_name
        FROM payment_verifications pv
        LEFT JOIN users u ON pv.user_id COLLATE utf8mb4_general_ci = u.id COLLATE utf8mb4_general_ci
        ORDER BY CASE WHEN pv.status = 'pending' THEN 0 ELSE 1 END, pv.id DESC";
$result = $db->con->query($sql);
if (!$result) {
    $out['list_query'] = 'FAILED: ' . $db->con->error;
} else {
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    $out['list_query'] = 'OK';
    $out['list_row_count'] = count($rows);
    $out['list_rows_sample'] = array_slice($rows, 0, 3);
}

// 6. Check upload directory
$uploadDir = __DIR__ . '/../uploads/payment_proofs/';
$out['upload_dir_path'] = realpath($uploadDir) ?: $uploadDir . ' (DOES NOT EXIST)';
$out['upload_dir_exists'] = is_dir($uploadDir) ? 'YES' : 'NO';
$out['upload_dir_writable'] = is_writable($uploadDir) ? 'YES' : 'NO (cannot write)';

if (is_dir($uploadDir)) {
    $files = array_diff(scandir($uploadDir), ['.', '..', '.htaccess']);
    $out['uploaded_files'] = array_values($files);
    $out['uploaded_file_count'] = count($files);
} else {
    $out['uploaded_files'] = [];
    $out['uploaded_file_count'] = 0;
}

// 7. proof_url regex test
$samplePath = 'uploads/payment_proofs/proof_12345_1753456789_abcd1234.jpg';
$out['proof_url_regex_test'] = preg_match('#^uploads/payment_proofs/[A-Za-z0-9._-]+$#', $samplePath)
    ? 'MATCHES (regex works)'
    : 'NO MATCH (regex broken)';

echo json_encode($out, JSON_PRETTY_PRINT);
