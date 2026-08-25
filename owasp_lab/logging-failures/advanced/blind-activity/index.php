<?php
/**
 * Nexora Audit - Shadow Infrastructure Manager
 * // secure logging enabled for all visible identities
 */

session_start();

$logs = file_exists('shadow_audit.json') ? json_decode(file_get_contents('shadow_audit.json'), true) : [];

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    
    // VULNERABILITY (A09): Blind Activity (Missing Critical Logs)
    // Only 'NORMAL' actions are logged. 'ELEVATED' or 'SHADOW' actions are skipped.
    
    if (strpos($action, 'SHADOW') === false) {
        $logs[] = [
            'time' => date('H:i:s'),
            'type' => 'INFO',
            'msg' => "User performed action: $action"
        ];
        file_put_contents('shadow_audit.json', json_encode($logs));
    }
    
    if ($action === 'SHADOW_REVEAL_FLAG') {
        $msg = "ROOT_ACCESS_OBTAINED: FLAG{a09_blind_activity_05}";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Shadow Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #0c0a09; color: #d6d3d1; font-family: 'Consolas', monospace; }</style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 800px;">
        <h4 class="mb-5 text-white fw-bold">Shadow-Ops Authorization Module</h4>
        
        <?php if(isset($msg)): ?><div class="alert alert-warning font-monospace py-4 rounded-4 shadow-lg"><?= $msg ?></div><?php endif; ?>

        <div class="row g-4 mb-4">
            <div class="col-md-7">
                <div class="card p-4 border-0 shadow bg-stone-900 text-stone-300 h-100 rounded-4">
                    <h6 class="fw-bold text-white mb-4 x-small uppercase opacity-50">Operation Console</h6>
                    <form method="POST">
                        <button name="action" value="NORMAL_PING" class="btn btn-outline-secondary w-100 mb-3 text-start small border-stone-800">1. PERFORM_NETWORK_PING (Logged)</button>
                        <button name="action" value="NORMAL_STATUS" class="btn btn-outline-secondary w-100 mb-3 text-start small border-stone-800">2. QUERY_INFRA_STATUS (Logged)</button>
                        <button name="action" value="SHADOW_REVEAL_FLAG" class="btn btn-outline-danger w-100 mb-0 text-start small border-stone-800 opacity-25 hvr-opacity-100 transition">3. [RESTRICTED] REVEAL_INFRA_FLAG (Blind)</button>
                    </form>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card p-4 border-0 shadow bg-stone-950 text-stone-500 h-100 rounded-4">
                    <h6 class="fw-bold text-white mb-4 x-small uppercase opacity-50">Visible Audit Trail</h6>
                    <div style="height: 200px; overflow-y: auto;">
                        <?php foreach(array_reverse($logs) as $l): ?>
                            <div class="x-small mb-2 fw-bold text-success">> [<?= $l['time'] ?>] <?= $l['msg'] ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center x-small opacity-20">
            Design Flaw: Shadow commands leave zero footprint in the operational registry.
        </div>
    </div>
</body>
</html>
