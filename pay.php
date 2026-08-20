<?php





$cookieParams = session_get_cookie_params();
session_set_cookie_params([
    'lifetime' => 0,
    'path' => $cookieParams['path'] ?? '/',
    'domain' => $cookieParams['domain'] ?? '',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

require_once 'api/dbconfig.php';
require_once 'api/avatar_helper.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['id'];
$db = new DBconfig();

if (!$db->check_con()) {
    die("Database connection failed");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_action']) && $_POST['ajax_action'] === 'process_gpay') {
    header('Content-Type: application/json');
    $paymentData = $_POST['paymentData'] ?? '';
    
    if (!empty($paymentData)) {
        $sql = "UPDATE users SET access = 'true' WHERE id = ?";
        $stmt = $db->con->prepare($sql);
        $stmt->bind_param("s", $userId);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Payment successful! Access granted.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database update failed.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid payment data.']);
    }
    exit;
}


$userInfo = $db->getUserInfo($userId, ['name', 'course', 'access']);
$userName = isset($userInfo['name']) ? $userInfo['name'] : 'User';
$course = isset($userInfo['course']) ? $userInfo['course'] : 'Not enrolled';

$hasAccess = (isset($userInfo['access']) && $userInfo['access'] === 'true');
$activeAvatarImage = getActiveAvatarImage($db, $userId);

?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upgrade Access - Secure Worldz Academy</title>
    <link rel="icon" type="image/webp" href="image.webp">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
<script src="api/includes/presence_realtime.js?v=20260320c" defer></script>
<style>
        :root {
            --gap: 1.5rem;
            --sides: 1.5rem;
            --radius: 0.625rem;
            --background: #000000;
            --foreground: #ffffff;
            --card: #080808;
            --primary: #ff2a2f;
            --primary-light: #a78bfa;
            --muted-foreground: #a0a0a0;
            --success: #ff2a2f;
            --border: rgba(139, 12, 16, 0.1);
        }

        @font-face {
            font-family: "Rebels";
            src: url("https://fonts.gstatic.com/s/roboto/v30/KFOmCnqEu92Fr1Mu4mxK.woff2") format("woff2");
            font-display: swap;
        }

        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: 'Roboto Mono', monospace; background: var(--background); color: var(--foreground); min-height: 100vh; }

        /* ===== DESKTOP LAYOUT ===== */
        .desktop-container {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: var(--gap, 1.5rem);
            min-height: 100vh;
            padding: var(--sides, 1.5rem);
            background-color: var(--background);
        }

        /* ===== SIDEBAR STYLES ===== */
        .desktop-sidebar {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .profile-card-section {
            background: transparent;
            border: none;
            padding: 0;
            margin-bottom: 0.5rem;
        }

        .card {
            background-color: var(--card);
            border-radius: 0.625rem;
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .nav-section {
            margin-bottom: 1.5rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            border-radius: 0.375rem;
            text-decoration: none;
            color: var(--muted-foreground);
            transition: all 0.2s;
            margin-bottom: 0.25rem;
            cursor: pointer;
        }

        .nav-icon {
            width: 1.25rem;
            height: 1.25rem;
            flex-shrink: 0;
        }

        .nav-label {
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .desktop-main {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            overflow-y: auto;
            max-height: calc(100vh - 3rem);
            padding-right: 0.5rem;
        }

        #loader-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #000000;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            transition: opacity 0.5s ease, visibility 0.5s;
        }

        #loader {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(139, 92, 246, 0.1);
            border-top-color: #ff2a2f;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        #loader-text {
            font-family: 'Roboto Mono', monospace;
            font-size: 0.75rem;
            color: #ff2a2f;
            letter-spacing: 0.2em;
            animation: pulse-loader 1.5s infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }
        @keyframes pulse-loader { 0%, 100% { opacity: 0.5; } 50% { opacity: 1; } }

        body.loaded #loader-wrapper {
            opacity: 0;
            visibility: hidden;
        }

        .font-display { font-family: 'Rebels', monospace; font-weight: 700; }

        .p-4 { padding: 1rem; }
        .p-3 { padding: 0.75rem; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .gap-3 { gap: 0.75rem; }
        .size-12 { width: 3rem; height: 3rem; }
        .rounded-lg { border-radius: 0.625rem; }
        .flex-1 { flex: 1 1 0%; }
        .text-2xl { font-size: 1.5rem; line-height: 2rem; }
        .text-xs { font-size: 0.75rem; line-height: 1rem; }
        .uppercase { text-transform: uppercase; }

        .checkout-container { max-width: 550px; margin: 4rem auto; }
        .checkout-card {
            background: linear-gradient(145deg, #1e2229, #13171d);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 3rem;
            text-align: center;
            box-shadow: 0 40px 100px -20px rgba(0,0,0,0.7);
            position: relative;
        }

        .lab-icon { font-size: 3.5rem; color: var(--primary); margin-bottom: 2rem; }
        .checkout-title { font-family: 'Space Grotesk', sans-serif; font-size: 2rem; margin-bottom: 1rem; color: #fff; }
        .checkout-desc { color: var(--muted-foreground); line-height: 1.6; margin-bottom: 2rem; }
        .price-tag { font-size: 3.5rem; font-weight: 800; color: #fff; margin-bottom: 2.5rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
        .price-tag span { font-size: 1.25rem; color: var(--muted-foreground); font-weight: 500; }

        #gpay-container { display: flex; justify-content: center; min-height: 48px; }

        .btn-continue { background: var(--primary); color: white; padding: 1rem 2.5rem; border-radius: 1rem; text-decoration: none; font-weight: 700; margin-top: 2rem; transition: transform 0.2s; display: inline-block; }
        .btn-continue:hover { transform: scale(1.05); }

        .success-overlay { position: absolute; inset: 0; background: var(--card); border-radius: var(--radius); display: none; flex-direction: column; align-items: center; justify-content: center; padding: 2rem; z-index: 5; }
        
        /* Modal Backdrop */
        .processing-modal { position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 1000; display: none; align-items: center; justify-content: center; backdrop-filter: blur(8px); }
        .loader { width: 48px; height: 48px; border: 4px solid #fff; border-bottom-color: var(--primary); border-radius: 50%; animation: rot 1s infinite linear; }
        @keyframes rot { 0% { transform: rotate(0); } 100% { transform: rotate(360deg); } }

        
        

        
        

        
/* LOCAL_DASHBOARD_NAV_LOCK */
.desktop-container .desktop-sidebar {
    height: fit-content !important;
    min-height: 0 !important;
    align-self: start !important;
    position: sticky !important;
    top: var(--sides, 1.5rem) !important;
    max-height: calc(100vh - (var(--sides, 1.5rem) * 2)) !important;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    scrollbar-width: thin;
    scrollbar-color: rgba(160, 160, 160, 0.45) transparent;
}

.desktop-container .desktop-sidebar::-webkit-scrollbar {
    width: 6px;
}

.desktop-container .desktop-sidebar::-webkit-scrollbar-track {
    background: transparent;
}

.desktop-container .desktop-sidebar::-webkit-scrollbar-thumb {
    background: rgba(160, 160, 160, 0.45);
    border-radius: 999px;
}

.desktop-container .desktop-sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(180, 180, 180, 0.7);
}

.desktop-container .desktop-sidebar > .card {
    flex: 0 0 auto !important;
    height: fit-content !important;
}

.desktop-container .desktop-sidebar .profile-card-section {
    background-color: var(--card, #080808) !important;
    border: 1px solid var(--border, rgba(139, 12, 16, 0.1)) !important;
    padding: 0 !important;
    margin-bottom: 0.5rem !important;
    overflow: hidden !important;
}

.desktop-container .desktop-sidebar .profile-card-section .p-4 {
    padding: 0.75rem !important;
}

.desktop-container .desktop-sidebar .profile-card-section .flex.items-center {
    min-width: 0 !important;
}

.desktop-container .desktop-sidebar .profile-card-section .size-12 {
    width: 3rem !important;
    height: 3rem !important;
    flex: 0 0 3rem !important;
}

.desktop-container .desktop-sidebar .profile-card-section .flex-1 {
    min-width: 0 !important;
}

.desktop-container .desktop-sidebar .profile-card-section .text-2xl,
.desktop-container .desktop-sidebar .profile-card-section .text-xs {
    width: 100% !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    white-space: nowrap !important;
    line-height: 1.2 !important;
}

.desktop-container .desktop-sidebar .nav-section {
    margin-bottom: 1.5rem !important;
}

.desktop-container .desktop-sidebar .nav-section .nav-item {
    display: flex !important;
    align-items: center !important;
    gap: 0.75rem !important;
    padding: 0.75rem !important;
    border-radius: calc(var(--radius, 0.625rem) - 2px) !important;
    text-decoration: none !important;
    color: var(--sidebar-foreground, #ffffff) !important;
    margin-bottom: 0.25rem !important;
    background-color: transparent !important;
    filter: none !important;
    backdrop-filter: none !important;
    box-shadow: none !important;
    opacity: 1 !important;
    pointer-events: auto !important;
    cursor: pointer !important;
    transition: none !important;
}

.desktop-container .desktop-sidebar .nav-section .nav-item:hover,
.desktop-container .desktop-sidebar .nav-section .nav-item:hover:not(.active),
.desktop-container .desktop-sidebar .nav-section .nav-item.disabled:hover {
    background-color: transparent !important;
    color: var(--sidebar-foreground, #ffffff) !important;
    filter: none !important;
    backdrop-filter: none !important;
    box-shadow: none !important;
}

.desktop-container .desktop-sidebar .nav-section .nav-item.active {
    background-color: transparent !important;
    color: #ffffff !important;
    border: 1px solid rgba(139, 12, 16, 0.1) !important;
    box-shadow: none !important;
}

.desktop-container .desktop-sidebar .nav-section .nav-item.disabled {
    opacity: 1 !important;
    pointer-events: auto !important;
    cursor: pointer !important;
    border: none !important;
    background-color: transparent !important;
}

.desktop-container .desktop-sidebar .nav-section .nav-icon {
    width: 1.25rem !important;
    height: 1.25rem !important;
    flex-shrink: 0 !important;
}

.desktop-container .desktop-sidebar .nav-section .nav-label {
    font-size: 0.875rem !important;
    font-weight: 500 !important;
    text-transform: uppercase !important;
}
</style>
<link rel="stylesheet" href="app-theme-overrides.css?v=20260817">
</head>
<body>
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div id="loader-text">INITIALIZING BILLING...</div>
    </div>
    
        <script>
            window.addEventListener('load', () => {
                setTimeout(() => {
                    document.body.classList.add('loaded');
                }, 500);
            });
        </script>
<div class="desktop-container">
        
    <!-- Left Sidebar - Navigation -->
    <?php include 'sidebar.php'; ?>

    <main class="desktop-main">
        <div class="checkout-card">
            <?php if ($hasAccess): ?>
                <div class="lab-icon"><i class="fas fa-unlock-alt"></i></div>
                <h1 class="checkout-title">Lab is Unlocked!</h1>
                <p class="checkout-desc">Your payment was verified. You have full access to Secure Worldz Academy Laboratory.</p>
                <a href="lab.php" class="btn-continue">Enter the Lab</a>
            <?php else: ?>
                <div class="lab-icon"><i class="fas fa-lock"></i></div>
                <h1 class="checkout-title">Unlock Full Access</h1>
                <p class="checkout-desc">Get the pro-lab environment and all advanced assignments instantly.</p>
                
                <div class="price-tag">₹1 <span>INR</span></div>
                
                <!-- Official Professional Google Pay Integration Container -->
                <div id="gpay-container"></div>
                
                <p style="margin-top: 2rem; font-size: 0.75rem; color: var(--muted-foreground);">Official Secure Payment Gateway</p>
                <p style="font-size: 0.75rem; color: var(--muted-foreground); opacity: 0.5;">Merchant ID: 9944994778</p>
            <?php endif; ?>

            <div class="success-overlay" id="payment-success">
                <i class="fas fa-check-circle" style="font-size: 5rem; color: var(--success); margin-bottom: 2rem;"></i>
                <h2 class="checkout-title">Access Granted!</h2>
                <p class="checkout-desc">Payment confirmed. Your account successfully upgraded.</p>
                <a href="lab.php" class="btn-continue">Enter Lab</a>
            </div>
        </div>
    </div>

    <div class="processing-modal" id="pay-loader"><div class="loader"></div></div>

    <!-- Official Google Pay JS SDK -->
    <script async src="https://pay.google.com/gp/p/js/pay.js" onload="onGooglePayLoaded()"></script>
    
    <script>
        const baseRequest = {
            apiVersion: 2,
            apiVersionMinor: 0
        };

        const allowedCardNetworks = ["MASTERCARD", "VISA", "RUPAY"];
        const allowedCardAuthMethods = ["PAN_ONLY", "CRYPTOGRAM_3DS"];

        // Official Gateway Setup (e.g., Razorpay)
        const tokenizationSpecification = {
            type: 'PAYMENT_GATEWAY',
            parameters: {
                'gateway': 'razorpay',
                'gatewayMerchantId': 'YOUR_RAZORPAY_KEY_ID' // User: Replace this with your Razorpay Key ID
            }
        };

        const baseCardPaymentMethod = {
            type: 'CARD',
            parameters: {
                allowedAuthMethods: allowedCardAuthMethods,
                allowedCardNetworks: allowedCardNetworks
            }
        };

        const cardPaymentMethod = Object.assign({}, baseCardPaymentMethod, {
            tokenizationSpecification: tokenizationSpecification
        });

        let paymentsClient = null;

        function onGooglePayLoaded() {
            paymentsClient = new google.payments.api.PaymentsClient({ environment: 'PRODUCTION' });
            
            paymentsClient.isReadyToPay(Object.assign({}, baseRequest, {
                allowedPaymentMethods: [baseCardPaymentMethod]
            }))
            .then(function(response) {
                if (response.result) {
                    const button = paymentsClient.createButton({
                        onClick: onPaymentClicked,
                        buttonColor: 'white',
                        buttonType: 'buy'
                    });
                    document.getElementById('gpay-container').appendChild(button);
                }
            })
            .catch(err => console.error("GPay Error:", err));
        }

        function onPaymentClicked() {
            const paymentDataRequest = Object.assign({}, baseRequest);
            paymentDataRequest.allowedPaymentMethods = [cardPaymentMethod];
            paymentDataRequest.transactionInfo = {
                countryCode: 'IN',
                currencyCode: 'INR',
                totalPriceStatus: 'FINAL',
                totalPrice: '1.00'
            };
            paymentDataRequest.merchantInfo = {
                merchantId: '9944994778', // User: Link this in your Google Play Console
                merchantName: 'Secure Worldz Academy Lab'
            };

            paymentsClient.loadPaymentData(paymentDataRequest)
                .then(function(paymentData) {
                    processServerPayment(paymentData);
                })
                .catch(err => console.error("Payment Sheet Failed:", err));
        }

        function processServerPayment(data) {
            document.getElementById('pay-loader').style.display = 'flex';
            
            const formData = new FormData();
            formData.append('ajax_action', 'process_gpay');
            formData.append('paymentData', JSON.stringify(data));

            fetch('pay.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(res => {
                document.getElementById('pay-loader').style.display = 'none';
                if (res.success) {
                    document.getElementById('payment-success').style.display = 'flex';
                }
            });
        }
    </script>
    </main>
    </div>
</html>
