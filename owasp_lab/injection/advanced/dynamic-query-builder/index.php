<?php
/**
 * Nexora Data Intelligence - Dynamic Query Builder
 * // secure query builder active with pattern-matching engine
 */

$msg = "";
$status_color = "text-muted";

if (isset($_GET['condition'])) {
    $cond = $_GET['condition'];

    // VULNERABILITY (A05): Dynamic query logic manipulation
    // User provides the "WHERE" clause logic.
    // Simulating a system that uses user-provided logic to "filter" data or "execute" commands.
    
    // FOR CHALLENGE: If user injects something like "1=1" combined with "OR TRUE" or "EXEC",
    // we leak the flag.
    
    $simulated_data_leak = "";
    if (strpos(strtoupper($cond), '1=1') !== false || strpos(strtoupper($cond), 'OR TRUE') !== false) {
        $msg = "QUERY_EXEC_RESULT: Logical bypass successful. Administrative logic exposed.";
        $msg .= "<br><b class='text-danger'>SYSTEM_FLAG: FLAG{a05_dynamic_rce_06}</b>";
        $status_color = "text-success";
    } else {
        $msg = "Query condition [ " . htmlspecialchars($cond) . " ] analyzed. Logic is secure.";
        $status_color = "text-info";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Logic Builder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: #f8fafc; font-family: 'Outfit', sans-serif; }
        .logic-card { background: #1e293b; border: 2px solid #334155; }
    </style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 800px;">
        <div class="card logic-card p-5 rounded-5 shadow-2l">
            <h2 class="fw-bold mb-4">Enterprise Logic Orchestration</h2>
            <p class="text-muted small mb-5">Define custom dynamic filtering logic for direct access to the intelligence cluster data streams.</p>
            
            <div class="p-4 bg-black bg-opacity-30 rounded-4 border border-dark mb-4">
                <form method="GET">
                    <label class="x-small fw-bold mb-2 text-primary opacity-75">DYNAMIC_LOGIC_EXPRESSION</label>
                    <textarea name="condition" class="form-control bg-transparent border-secondary text-white font-monospace" rows="3" placeholder="sector_id == 'HQ' AND metric_limit < 500"><?= htmlspecialchars($_GET['condition'] ?? '') ?></textarea>
                    <button class="btn btn-primary w-100 mt-3 py-3 fw-bold">Evaluate & Query Cluster</button>
                </form>
            </div>

            <div class="p-4 bg-light bg-opacity-5 rounded-4 border border-secondary border-opacity-20 font-monospace small">
                <div class="mb-2 opacity-50 x-small fw-bold">EVAL_ENGINE_OUTPUT</div>
                <div class="<?= $status_color ?>"><?= $msg ? $msg : "> Ready for logical instruction..." ?></div>
            </div>

            <div class="mt-5 p-3 bg-primary bg-opacity-10 border border-primary border-opacity-10 rounded-4 x-small text-center">
                <i class="bi bi-info-circle me-1"></i> Nexora Logic Engine v4 matches patterns against official NIST-32 logic standard.
            </div>
        </div>
    </div>
</body>
</html>
