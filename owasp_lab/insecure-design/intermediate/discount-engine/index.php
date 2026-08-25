<?php
/**
 * Nexora Business Workflow - Corporate Discount Engine
 * // price calculation engine active
 */

session_start();

$price = 499.00;
$discount = isset($_GET['rate']) ? (int)$_GET['rate'] : 0;

// VULNERABILITY (A06): Insecure Design - Client-controlled business logic
// User can set 'rate=100' to get the software for free.
// The backend trusts the 'rate' parameter supplied by the client without upper-bound validation.

$total = $price - ($price * ($discount / 100));
$msg = "";

if ($discount >= 100) {
    $msg = "<div class='p-3 bg-danger bg-opacity-10 text-danger border border-danger-subtle rounded mb-4 small'>";
    $msg .= "<b>NEXORA_FINANCE_ALERT:</b> Zero-cost transaction allowed under enterprise partner agreement. <br><b>PROMO_FLAG: FLAG{a06_discount_abuse_03}</b>";
    $msg .= "</div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora | Billing & Discounts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background: #fdfdfd; font-family: 'Segoe UI', sans-serif; }</style>
</head>
<body class="p-5">
    <div class="container" style="max-width: 600px;">
        <div class="card border-0 shadow-lg p-5 rounded-5 mt-5">
            <h2 class="fw-bold mb-4">Enterprise Checkout</h2>
            <?= $msg ?>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Software Subscription (Annual)</span>
                <span class="fw-bold text-dark">$<?= $price ?>.00</span>
            </div>
            <div class="d-flex justify-content-between text-success mb-4 pb-4 border-bottom">
                <span>Enterprise Discount (<?= $discount ?>%)</span>
                <span>-$<?= number_format($price * ($discount / 100), 2) ?></span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="fw-bold m-0">Total Due</h4>
                <h3 class="fw-bold m-0 text-primary">$<?= number_format($total, 2) ?></h3>
            </div>
            <button class="btn btn-dark w-100 py-3 mt-5 fw-bold rounded shadow">Finalize Transaction</button>
        </div>
        
        <div class="mt-4 p-4 text-center small text-muted">
            Internal Note: High-volume partners can apply discounts via the <code>rate</code> parameter in the partner URL.
        </div>
    </div>
</body>
</html>
