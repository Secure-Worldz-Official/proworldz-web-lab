<?php
/**
 * Nexora DevOps - Core Dynamic Loader
 * // secure module inclusion authenticated
 */

$output = "";
if (isset($_GET['module'])) {
    $module = $_GET['module'];

    // VULNERABILITY (A03): Path manipulation in dynamic loader
    // Attacker can use ../ traversal if no whitelist is present
    
    if (strpos($module, 'flag') !== false || strpos($module, 'config') !== false) {
        $output = "System Security: Access to restricted filesystem block blocked.<br><b>FLAG LOGGED: FLAG{a03_loader_hijack_05}</b>";
    } else {
        $output = "Module [ " . htmlspecialchars($module) . " ] initiated. (Loading from internal path: modules/$module)";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Module Loader</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: #94a3b8; font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 700px;">
        <div class="card bg-slate-900 border border-slate-800 p-5 rounded-5 shadow-2xl">
            <h2 class="text-white fw-bold mb-4">Core Module Orchestrator</h2>
            <p class="small mb-5">Dynamically load internal infrastructure modules for system configuration.</p>
            
            <div class="p-4 bg-slate-950 rounded-4 border border-slate-800 mb-4">
                <form method="GET">
                    <label class="x-small fw-bold mb-2">TARGET MODULE IDENTIFIER</label>
                    <div class="input-group">
                        <input type="text" name="module" class="form-control bg-transparent border-slate-700 text-white" placeholder="network_config.json" value="<?= htmlspecialchars($_GET['module'] ?? '') ?>">
                        <button class="btn btn-primary px-4 fw-bold shadow-lg">Load Module</button>
                    </div>
                </form>
            </div>

            <?php if($output): ?>
            <div class="p-4 bg-blue-900 bg-opacity-20 border border-blue-800 text-blue-400 rounded-4 font-monospace small animated fadeIn">
                <i class="bi bi-chevron-right me-2"></i> <?= $output ?>
            </div>
            <?php endif; ?>

            <div class="mt-5 text-center x-small opacity-50">
                Authorized by Nexora Internal Kernel v0.81
            </div>
        </div>
    </div>
</body>
</html>
