<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

function respond($statusCode, array $payload): void
{
    http_response_code($statusCode);
    echo json_encode($payload);
    exit;
}

if (empty($_SESSION['admin_username'])) {
    respond(403, ['success' => false, 'message' => 'Admin authentication is required.']);
}

require_once __DIR__ . '/../api/dbconfig.php';
$paymentConfig = require __DIR__ . '/../api/payment_config.php';
$db = new DBconfig();

if (!$db->con || $db->con->connect_error) {
    respond(500, ['success' => false, 'message' => 'Unable to connect to the database.']);
}

$action = $_POST['action'] ?? $_GET['action'] ?? 'list';

if ($action === 'list') {
    $db->ensurePaymentVerificationsTable();
    // Clear any error state left by the DDL above (InfinityFree / MySQL quirk)
    while ($db->con->more_results()) { $db->con->next_result(); }
    @$db->con->errno; // flush

    $sql = "SELECT pv.id, pv.user_id, pv.screenshot_path, pv.payment_method, pv.status, pv.created_at, pv.reviewed_at,
                   u.name AS student_name, u.course AS course_name
            FROM payment_verifications pv
            LEFT JOIN users u ON pv.user_id COLLATE utf8mb4_general_ci = u.id COLLATE utf8mb4_general_ci
            ORDER BY CASE WHEN pv.status = 'pending' THEN 0 ELSE 1 END, pv.id DESC";
    $result = $db->con->query($sql);
    if (!$result) {
        $dbErr = $db->con->error;
        error_log("OWASP 2026 admin list query failed: " . $dbErr);
        respond(500, ['success' => false, 'message' => 'DB query failed: ' . $dbErr]);
    }

    $requests = [];
    $duration = '1 Month (' . ($paymentConfig['billing_period'] ?? '30 days') . ')';
    while ($row = $result->fetch_assoc()) {
        $path = trim(str_replace('\\', '/', (string) ($row['screenshot_path'] ?? '')));
        if (!empty($path) && strpos($path, '..') === false) {
            $row['proof_url'] = (strpos($path, 'uploads/') === 0) ? '../' . $path : '../uploads/payment_proofs/' . basename($path);
        } else {
            $row['proof_url'] = null;
        }
        $row['student_name'] = $row['student_name'] ?: 'Unknown student';
        $row['course_name'] = (!empty($row['course_name'])) ? $row['course_name'] : 'OWASP 2026 AI Security Lab';
        $row['duration'] = $duration;
        $requests[] = $row;
    }

    if (empty($requests)) {
        $countResult = $db->con->query("SELECT COUNT(*) as total FROM payment_verifications");
        $totalRows = $countResult ? $countResult->fetch_assoc()['total'] : 'unknown';
        error_log("OWASP 2026 admin list returned 0 requests. Total rows in payment_verifications: " . var_export($totalRows, true));
    } else {
        error_log("OWASP 2026 admin list returned " . count($requests) . " requests.");
    }

    respond(200, ['success' => true, 'requests' => $requests]);
}

if ($action !== 'review') {
    respond(400, ['success' => false, 'message' => 'Invalid request action.']);
}

$csrfToken = (string) ($_POST['csrf_token'] ?? '');
if (empty($_SESSION['owasp2026_admin_csrf']) || !hash_equals($_SESSION['owasp2026_admin_csrf'], $csrfToken)) {
    respond(403, ['success' => false, 'message' => 'Invalid review request. Please refresh the page and try again.']);
}

$verificationId = filter_var($_POST['verification_id'] ?? null, FILTER_VALIDATE_INT);
$decision = $_POST['decision'] ?? '';
if (!$verificationId || !in_array($decision, ['approve', 'decline'], true)) {
    respond(422, ['success' => false, 'message' => 'Choose a valid submission and action.']);
}

$check = $db->con->prepare('SELECT status FROM payment_verifications WHERE id = ? LIMIT 1');
if (!$check) {
    respond(500, ['success' => false, 'message' => 'Unable to validate the payment request.']);
}
$check->bind_param('i', $verificationId);
$check->execute();
$submission = $check->get_result()->fetch_assoc();
$check->close();

if (!$submission) {
    respond(404, ['success' => false, 'message' => 'Payment request not found.']);
}
if ($submission['status'] !== 'pending') {
    respond(409, ['success' => false, 'message' => 'This payment request has already been reviewed.']);
}

$adminId = (string) $_SESSION['admin_username'];
$updated = $decision === 'approve'
    ? $db->acceptPaymentVerification($verificationId, $adminId)
    : $db->declinePaymentVerification($verificationId, $adminId);

if (!$updated) {
    respond(500, ['success' => false, 'message' => 'Unable to update the payment request.']);
}

respond(200, [
    'success' => true,
    'status' => $decision === 'approve' ? 'accepted' : 'declined',
    'label' => $decision === 'approve' ? 'Approved' : 'Declined',
    'message' => $decision === 'approve'
        ? 'Payment approved. OWASP 2026 lab access is now active for this student.'
        : 'Payment declined. OWASP 2026 lab access was not granted.'
]);
