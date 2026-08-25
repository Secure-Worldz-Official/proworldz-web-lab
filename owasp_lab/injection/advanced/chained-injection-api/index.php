<?php
/**
 * Nexora Data Intelligence - Chained API Processor
 * // dynamic query builder active with multi-stage sanitization
 */

$msg = "";
$response_code = "SEC_WAITING_FOR_INPUT";

if (isset($_GET['api_key']) && isset($_GET['query_type'])) {
    $key = $_GET['api_key'];
    $type = $_GET['query_type'];
    $params = $_GET['ext_params'] ?? '';

    // VULNERABILITY (A05): Chained parameter injection
    // Logic: system combines multiple parameters into a "secure instruction"
    // Exploitation requires manipulating both key and type to trigger hidden logic.
    
    $instruction = "TYPE:{$type}|KEY:{$key}|PARAMS:{$params}";
    
    if (strpos($instruction, 'ADMIN_ACCESS') !== false && strpos($instruction, 'OVERRIDE_KEY') !== false) {
        $msg = "CHAIN_INJECTION_SUCCESS: Identity confirmed for advanced intelligence query.";
        $msg .= "<br><b class='text-danger'>FLAG: FLAG{a05_chain_inject_05}</b>";
        $response_code = "SEC_ACCESS_AUTHORIZED_ROOT";
    } else {
        $msg = "Query processed version 8.1: Instruction set validated.";
        $response_code = "SEC_QUERY_EXECUTED_STABLE";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Advanced API</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; font-family: 'Consolas', monospace; }
        .api-card { border: 1px solid #e2e8f0; border-top: 5px solid #3b82f6; }
    </style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 800px;">
        <div class="card p-5 api-card shadow-lg rounded-4">
            <h4 class="fw-bold text-dark mb-4"><i class="bi bi-cpu-fill text-primary"></i> Nexora Intel API Interface</h4>
            <p class="text-muted small mb-5">Interface for secondary intelligence nodes to fetch sector-specific analytics data.</p>
            
            <form method="GET" class="row g-3 mb-5">
                <div class="col-md-6">
                    <label class="x-small fw-bold mb-1 opacity-50">API_KEY</label>
                    <input type="text" name="api_key" class="form-control" placeholder="X-NEX-0091" value="<?= htmlspecialchars($_GET['api_key'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="x-small fw-bold mb-1 opacity-50">QUERY_TYPE</label>
                    <input type="text" name="query_type" class="form-control" placeholder="ANALYTICS_V1" value="<?= htmlspecialchars($_GET['query_type'] ?? '') ?>">
                </div>
                <div class="col-12">
                    <label class="x-small fw-bold mb-1 opacity-50">EXT_PARAMS</label>
                    <input type="text" name="ext_params" class="form-control" placeholder="sector=US;limit=10">
                </div>
                <div class="col-12">
                    <button class="btn btn-primary w-100 py-3 fw-bold shadow">Initialize API Request</button>
                </div>
            </form>

            <div class="bg-dark text-success p-4 rounded-4 small">
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom border-secondary">
                    <span>NEXORA_API_GATEWAY</span>
                    <span class="text-primary"><?= $response_code ?></span>
                </div>
                <?= $msg ? $msg : "[Waiting for authorized instruction set...]" ?>
            </div>
            
            <div class="mt-4 text-center x-small text-muted opacity-50">
                Internal Documentation: <code>/api/v2/docs?token=predictable</code>
            </div>
        </div>
    </div>
</body>
</html>
