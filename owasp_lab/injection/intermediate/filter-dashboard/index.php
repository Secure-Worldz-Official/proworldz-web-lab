<?php
/**
 * Nexora Data Intelligence - Filter Dashboard
 * // secure query builder active
 */

$data = [
    ['id' => 101, 'metric' => 'Cloud Efficiency', 'val' => '94.2%', 'zone' => 'US-East'],
    ['id' => 102, 'metric' => 'Security Score', 'val' => '100%', 'zone' => 'Global'],
    ['id' => 103, 'metric' => 'Auth Latency', 'val' => '12ms', 'zone' => 'EU-West']
];

$zone = $_GET['zone'] ?? 'Global';

// VULNERABILITY (A05): Query logic based on unsafe GET parameter
// If the user injects logic-like parameters or bypasses filters.

$filtered = array_filter($data, function($item) use ($zone) {
    if ($zone === 'ALL') return true;
    return $item['zone'] === $zone;
});

// SECRET FLAG HINT
if (isset($_GET['filter_bypass']) && $_GET['filter_bypass'] === 'true') {
    $bypass_msg = "INTERNAL AUDIT PASS: FLAG{a05_filter_bypass_04}";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Metric Filters</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fdfdfd; font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="p-5">
    <div class="container">
        <h2 class="fw-bold mb-4">Enterprise Metric Filters</h2>
        
        <?php if(isset($bypass_msg)): ?>
            <div class="alert alert-warning border-0 shadow-sm p-4 rounded-4 mb-4">
                <h6 class="fw-bold text-dark">SECURE OVERRIDE GRANTED</h6>
                <p class="mb-0 small text-danger fw-bold"><?= $bypass_msg ?></p>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm p-4 rounded-4 mb-5">
            <div class="d-flex justify-content-between align-items-center">
                <form method="GET" class="d-flex gap-2">
                    <select name="zone" class="form-select w-auto">
                        <option value="Global" <?= $zone == 'Global' ? 'selected' : '' ?>>Global Infrastructure</option>
                        <option value="US-East" <?= $zone == 'US-East' ? 'selected' : '' ?>>US Sector</option>
                        <option value="EU-West" <?= $zone == 'EU-West' ? 'selected' : '' ?>>EU Sector</option>
                        <option value="ALL" <?= $zone == 'ALL' ? 'selected' : '' ?>>[MASTER_REVEAL_ALL]</option>
                    </select>
                    <button class="btn btn-dark">Apply Sector Filter</button>
                </form>
                <span class="x-small fw-bold opacity-30">Active Sector: <?= htmlspecialchars($zone) ?></span>
            </div>
        </div>

        <div class="row g-4">
            <?php foreach($filtered as $f): ?>
            <div class="col-md-4">
                <div class="card p-4 border-0 shadow-sm rounded-4 h-100 text-center">
                    <h1 class="fw-bold mb-1 display-5 text-primary"><?= $f['val'] ?></h1>
                    <h6 class="text-muted small fw-bold text-uppercase letter-spacing-1"><?= $f['metric'] ?></h6>
                    <hr class="my-4 opacity-5">
                    <span class="badge bg-light text-dark border p-2 px-3 small"><?= $f['zone'] ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
