<?php
/**
 * Nexora Integrity - Data Trust Engine
 * // sanitized data packet received
 */

session_start();

$trust_msg = "";
if (isset($_POST['data'])) {
    // VULNERABILITY (A08): Data Integrity Failure - Trusting serialized client data
    // The system decodes and trusts a JSON object from the client without any MAC/Signature.
    // Attacker can manipulate 'permissions' or 'access_level' inside the JSON.

    $packet = json_decode($_POST['data'], true);
    
    if ($packet && isset($packet['admin_override']) && $packet['admin_override'] === true) {
        $trust_msg = "<div class='card p-4 border-primary bg-primary bg-opacity-10 text-primary rounded-4 text-center'>";
        $trust_msg .= "<h4 class='fw-bold'>TRUST OVERRIDE GRANTED</h4>";
        $msg_body = "Global Data Lock: FLAG{a08_data_trust_04}";
        $trust_msg .= "<p class='mb-0 small font-monospace'>$msg_body</p>";
        $trust_msg .= "</div>";
    } else {
        $trust_msg = "<div class='alert alert-light border small text-center'>Data packet received. Standard clearance applied.</div>";
    }
}

// Initial packet for UI
$default_packet = json_encode([
    'timestamp' => time(),
    'session_token' => md5(session_id()),
    'admin_override' => false,
    'source' => 'node_882'
]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Data Trust</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #fbfbfb; font-family: 'Inter', sans-serif; }</style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 600px;">
        <div class="card border-0 shadow-lg p-5 rounded-5 bg-white">
            <h2 class="fw-bold mb-4 text-center">Data Integrity Gate</h2>
            
            <?= $trust_msg ?>

            <form method="POST" class="mt-4">
                <label class="x-small fw-bold opacity-50 mb-2">RAW_DATA_PACKET (JSON)</label>
                <textarea name="data" class="form-control font-monospace mb-4" rows="6"><?= htmlspecialchars($default_packet) ?></textarea>
                <button class="btn btn-dark w-100 py-3 fw-bold shadow">Submit and Verify Packet</button>
            </form>
            
            <div class="mt-4 p-3 bg-light rounded text-center x-small opacity-50 italic">
                “Trust but Verify” protocol v2.1 active.
            </div>
        </div>
    </div>
</body>
</html>
