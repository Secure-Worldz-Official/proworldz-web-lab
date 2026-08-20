<?php
require_once 'api/auth_check.php';






$categories = [
    ['id' => 1, 'title' => 'Broken Access Control',        'category' => 'A01', 'icon' => 'fa-shield-halved'],
    ['id' => 2, 'title' => 'Security Misconfiguration',      'category' => 'A02', 'icon' => 'fa-gears'],
    ['id' => 3, 'title' => 'Software Supply Chain Failures', 'category' => 'A03', 'icon' => 'fa-cubes'],
    ['id' => 4, 'title' => 'Cryptographic Failures',         'category' => 'A04', 'icon' => 'fa-key'],
    ['id' => 5, 'title' => 'Injection',                      'category' => 'A05', 'icon' => 'fa-terminal'],
    ['id' => 6, 'title' => 'Insecure Design',                'category' => 'A06', 'icon' => 'fa-pen-ruler'],
    ['id' => 7, 'title' => 'Authentication Failures',        'category' => 'A07', 'icon' => 'fa-user-lock'],
    ['id' => 8, 'title' => 'Software/Data Integrity Failures','category' => 'A08', 'icon' => 'fa-file-signature'],
    ['id' => 9, 'title' => 'Security Logging/Alerting Failures','category' => 'A09', 'icon' => 'fa-list-check'],
    ['id' => 10,'title' => 'Mishandling of Exceptional Conditions','category' => 'A10', 'icon' => 'fa-triangle-exclamation'],
];

$docs = [
    'Broken Access Control' => [
        'overview' => 'Failure to enforce restrictions on what authenticated users are allowed to do. Challenges include IDOR and multi-role bypass.',
        'impact' => 'Unauthorized data access, account takeover, and unintended modification of sensitive records.',
        'remediation' => 'Implement strict access control checks on the server-side for every request.',
        'vector' => 'Modification of URL parameters, session tokens, or API identifiers.'
    ],
    'Security Misconfiguration' => [
        'overview' => 'Insecure default settings, open cloud storage, and unmasked error messages containing sensitive info.',
        'impact' => 'Information disclosure of environment variables, internal paths, and access to unprotected admin features.',
        'remediation' => 'Harden configurations, disable debug modes, and use secure defaults across the stack.',
        'vector' => 'Exposed .git folders, backup files, or verbose error logs.'
    ],
    'Software Supply Chain Failures' => [
        'overview' => 'Using software from untrusted sources, including blind plugin loading and unverified updates.',
        'impact' => 'Remote code execution (RCE) via malicious dependencies or backdoored software updates.',
        'remediation' => 'Verify digital signatures, use trusted repositories, and audit third-party components.',
        'vector' => 'Compromised update mirrors or malicious packages.'
    ],
    'Cryptographic Failures' => [
        'overview' => 'Exposure of sensitive data due to weak encryption, hardcoded keys, or predictable tokens.',
        'impact' => 'Theft of PII, financial data, and credentials through decryption of weak algorithms.',
        'remediation' => 'Use strong algorithms (AES-256), secure key management, and collision-resistant hashes.',
        'vector' => 'Weak MD5/SHA1 hashes, Base64 secrets, or leaked static keys.'
    ],
    'Injection' => [
        'overview' => 'Untrusted data sent to an interpreter as part of a command (SQL, Log, or Command injection).',
        'impact' => 'Full database compromise, data exfiltration, and administrative bypass.',
        'remediation' => 'Use prepared statements, parameterized queries, and strict input validation.',
        'vector' => 'Concatenation of user input into raw command strings.'
    ],
    'Insecure Design' => [
        'overview' => 'Flaws in the architectural design of business logic and workflows.',
        'impact' => 'Logical bypasses of critical business steps, such as skipping payment or approval gateways.',
        'remediation' => 'Implement secure design patterns and validate all workflow steps on the server.',
        'vector' => 'Manipulating step sequences or client-side logic flags.'
    ],
    'Authentication Failures' => [
        'overview' => 'Insecure identity management, including weak login logic and predictable reset tokens.',
        'impact' => 'Account takeover, session hijacking, and brute-force access to restricted profiles.',
        'remediation' => 'Implement multi-factor authentication and secure session management.',
        'vector' => 'Bypassing login via logic flaws or predicting session identifiers.'
    ],
    'Software/Data Integrity Failures' => [
        'overview' => 'Assumptions made about data and updates without verifying its authenticity or state.',
        'impact' => 'Code execution via tampered updates or manipulation of critical settings.',
        'remediation' => 'Mandatory signature verification for all code and data updates.',
        'vector' => 'Forging signatures or intercepting unauthenticated data streams.'
    ],
    'Security Logging/Alerting Failures' => [
        'overview' => 'Inability to detect and respond to breaches due to insufficient logging or monitoring.',
        'impact' => 'Extended dwell time for attackers and inability to perform forensic analysis.',
        'remediation' => 'Log all security events and implement real-time alerting for anomalies.',
        'vector' => 'Silent brute-force or log injection to hide malicious activity.'
    ],
    'Mishandling of Exceptional Conditions' => [
        'overview' => 'Error handling that leaks sensitive data or bypasses security checks during crashes.',
        'impact' => 'Disclosure of system internals and bypasses in the execution flow.',
        'remediation' => 'Implement fail-safe error handling and mask technical details from end users.',
        'vector' => 'Inducing crashes to reveal environment configuration.'
    ],
];


$flags = [
    'a01_01' => 'FLAG{a01_idor_bypass_01}', 'a01_02' => 'FLAG{a01_profile_leak_02}', 'a01_03' => 'FLAG{a01_api_guess_03}', 'a01_04' => 'FLAG{a01_admin_bypass_04}', 'a01_05' => 'FLAG{a01_rbac_bypass_05}', 'a01_06' => 'FLAG{a01_horizontal_leak_06}',
    'a02_01' => 'FLAG{6d6973636f6e6669675f3032}', 'a02_02' => 'FLAG{6d6973636f6e6669675f3031}', 'a02_03' => 'FLAG{6d6973636f6e6669675f3033}', 'a02_04' => 'FLAG{6230315f3034}', 'a02_05' => 'FLAG{6d6973636f6e6669675f3036}', 'a02_06' => 'FLAG{a02_06_todo}',
    'a03_01' => 'FLAG{a03_plugin_loader_01}', 'a03_02' => 'FLAG{a03_update_fetch_02}', 'a03_03' => 'FLAG{a03_package_trust_03}', 'a03_04' => 'FLAG{a03_marketplace_bypass_04}', 'a03_05' => 'FLAG{a03_loader_hijack_05}', 'a03_06' => 'FLAG{a03_update_bypass_06}',
    'a04_01' => 'FLAG{a04_01_todo}', 'a04_02' => 'FLAG{a04_02_todo}', 'a04_03' => 'FLAG{a04_token_predict_03}', 'a04_04' => 'FLAG{a04_crypto_bypass_04}', 'a04_05' => 'FLAG{a04_key_exposure_05}', 'a04_06' => 'FLAG{a04_crypto_break_06}',
    'a05_01' => 'FLAG{a05_sql_inject_01}', 'a05_02' => 'FLAG{a05_report_inject_02}', 'a05_03' => 'FLAG{a05_filter_bypass_04}', 'a05_04' => 'FLAG{a05_log_inject_03}', 'a05_05' => 'FLAG{a05_chain_inject_05}', 'a05_06' => 'FLAG{a05_dynamic_rce_06}',
    'a06_01' => 'FLAG{a06_flow_bypass_01}', 'a06_02' => 'FLAG{a06_reset_skip_02}', 'a06_03' => 'FLAG{a06_discount_abuse_03}', 'a06_04' => 'FLAG{a06_feature_unlock_04}', 'a06_05' => 'FLAG{a06_multi_step_skip_05}', 'a06_06' => 'FLAG{a06_role_confusion_06}',
    'a07_01' => 'FLAG{a07_login_bypass_01}', 'a07_02' => 'FLAG{a07_user_enum_02}', 'a07_03' => 'FLAG{a07_weak_session_03}', 'a07_04' => 'FLAG{a07_reset_predict_04}', 'a07_05' => 'FLAG{a07_fixation_bypass_05}', 'a07_06' => 'FLAG{a07_chain_bypass_06}',
    'a08_01' => 'FLAG{a08_config_tamper_01}', 'a08_02' => 'FLAG{a08_hash_bypass_02}', 'a08_03' => 'FLAG{a08_update_inject_03}', 'a08_04' => 'FLAG{a08_data_trust_04}', 'a08_05' => 'FLAG{a08_signature_bypass_05}', 'a08_06' => 'FLAG{a08_integrity_chain_06}',
    'a09_01' => 'FLAG{a09_missing_logs_01}', 'a09_02' => 'FLAG{a09_weak_logging_02}', 'a09_03' => 'FLAG{a09_log_tamper_03}', 'a09_04' => 'FLAG{a09_alert_bypass_04}', 'a09_05' => 'FLAG{a09_blind_activity_05}', 'a09_06' => 'FLAG{a09_log_injection_06}',
    'a10_01' => 'FLAG{a10_missing_validation_02}', 'a10_02' => 'FLAG{a10_verbose_error_01}', 'a10_03' => 'FLAG{a10_exception_ignore_03}', 'a10_04' => 'FLAG{a10_fallback_bypass_04}', 'a10_05' => 'FLAG{a10_chain_break_05}', 'a10_06' => 'FLAG{a10_crash_exploit_06}',
];

$scenarios = [
    
    'a01_01' => 'Nexora\'s document portal allows users to view public files. However, we suspect sensitive notes are stored in the same repository. Can you find the private flag?',
    'a01_02' => 'The employee profile system allows viewing bios by ID. It seems they forgot to check who is viewing what. Can you leak the flag from a restricted profile?',
    'a01_03' => 'The internal API uses predictable numeric identifiers. Can you enumerate the resources and discover Nexora\'s hidden intelligence reports?',
    'a01_04' => 'A management console was left online. It checks for a session, but does it check your role? Try to reach the admin core and extract the flag.',
    'a01_05' => 'Access control lists are notoriously hard to configure. See if you can manipulate your session roles to view master records reserved for executives.',
    'a01_06' => 'Reports are supposed to be personal, but the viewer doesn\'t seem to verify ownership. Can you access reports belonging to other employees?',

    
    'a02_01' => 'A developer left a configuration file publicly readable. Can you find the "config.php" and extract the administrative API keys?',
    'a02_02' => 'A debug panel was accidentally left enabled in production. Use it to dump internal server states and find the hidden flag.',
    'a02_03' => 'Verbose errors can be a goldmine. Trigger a system crash to force the application to reveal its internal directory structure.',
    'a02_04' => 'Headers protect against various attacks, but this site seems to be missing them. Identify a missing policy and leak the sensitive flag.',
    'a02_05' => 'Backup files are often left behind after maintenance. Try to guess common backup names and recover secrets from old archive data.',
    'a02_06' => 'Routing rules are complex. Can you find a crafted URL that bypasses the primary gateway to reach the internal admin subnets?',

    
    'a03_01' => 'The DevOps hub allows loading plugins from mirrors. I wonder if it verifies the mirror\'s reputation? Deploy a malicious module and capture the flag.',
    'a03_02' => 'The update center fetches packages automatically. Can you poison the update repository to force a backdoored package onto the infrastructure?',
    'a03_03' => 'Marketplace providers are trusted implicitly. Find a way to inject unverified code into the application lifecycle via a provider registry flaw.',
    'a03_04' => 'Package metadata is easily manipulated. Trick the system into installing a tampered dependency by spoofing its reputation.',
    'a03_05' => 'Hidden in the library initialization is a dynamic loader. Can you hijack the execution flow during system startup?',
    'a03_06' => 'Digital signatures ensure integrity, but the validation logic here is flawed. Bypass the check and sign your own malicious instructions.',

    
    'a04_01' => 'Nexora uses a very old hashing algorithm for its vault. Can you reverse the hashes and recover the administrator\'s credentials?',
    'a04_02' => 'Sensitive data is being "encrypted" using a simple format. Decode the Base64-encoded secrets and find the mission flag.',
    'a04_03' => 'Predictability is the enemy of crypto. Analyze the token generation logic and predict the next valid session identifier.',
    'a04_04' => 'We found a proprietary cipher engine. It looks like they tried to be clever. Can you break the custom encryption and read the flag?',
    'a04_05' => 'Developers sometimes hide keys in plain sight. Search the source code for hardcoded cryptographic keys and unlock the secret vault.',
    'a04_06' => 'Flaws in signature comparison can lead to catastrophic failure. Forge a valid signature by exploiting a loose comparison in the validator.',

    
    'a05_01' => 'The employee search is direct and fast, but is it secure? Inject SQL commands to dump the entire employees table and find Alice\'s secrets.',
    'a05_02' => 'HR reports use dynamic filters. Manipulate the "dept" parameter to perform a SQL injection and view confidential salary data.',
    'a05_03' => 'Filters are in place to block "SELECT" and "UNION". Can you find a way to bypass this blacklist and leak the database flag?',
    'a05_04' => 'The system logs everything you search for. Can you inject newlines and fake log entries to poison the monitoring dashboard?',
    'a05_05' => 'Sometimes input is stored and used later. Find a way to perform a second-order injection that executes a command in a different context.',
    'a05_06' => 'The query builder is dynamic but dangerous. Can you use blind SQL injection to leak system secrets character by character?',

    
    'a06_01' => 'The corporate approval flow has multiple steps. Can you skip the manager\'s review and go straight to the final authorization?',
    'a06_02' => 'The password reset flow is supposed to be secure. Identify a logical flaw that allows you to skip the identity verification phase.',
    'a06_03' => 'Discounts are great, but this engine has a design flaw. Can you manipulate the quantity logic to get high-value items for free?',
    'a06_04' => 'Premium features are locked behind a paywall. I wonder if the lock is only on the client side? Bypass the check and unlock the hub.',
    'a06_05' => 'Asset allocation handles company resources. Can you manipulate the step sequence to assign resources directly to your account?',
    'a06_06' => 'Role confusion occurs when identities overlap. Can you trick the account management system into assigning you an admin role via an invitation link?',

    
    'a07_01' => 'A insecure login portal is just waiting to be bypassed. Find a logical flaw in the authentication sequence and capture the flag.',
    'a07_02' => 'Error messages can tell you more than you think. Enumerate valid user IDs by analyzing how the system responds to different inputs.',
    'a07_03' => 'Session identifiers should be unique and random. If they aren\'t, you might be able to hijack someone else\'s session. Try it!',
    'a07_04' => 'Analyze how reset tokens are generated. If you can predict the next token, you can take over any account on the platform.',
    'a07_05' => 'In a session fixation attack, the attacker chooses the session ID. Can you force a target into using a known ID before they log in?',
    'a07_06' => 'Why settle for one flaw? Combine multiple authentication weaknesses to perform a full bypass of the Nexora IAM orchestrator.',

    
    'a08_01' => 'Corporate configurations are sent as raw data. If you can tamper with them during transmission, you can change how the system behaves.',
    'a08_02' => 'The server verify files with MD5, but it trusts the user to provide the hash. Can you provide a malicious file with a valid "user-provided" hash?',
    'a08_03' => 'The deployment orchestrator trusts incoming package data too much. Inject malicious instructions into the trust-based update stream.',
    'a08_04' => 'Modify the application state during an automated update cycle to inject a backdoor without being detected by the integrity monitor.',
    'a08_05' => 'The signature verification chain is broken. Find a way to authorize an untrusted software update as if it were legitimate.',
    'a08_06' => 'An automated script replaces system libraries from a central repo. Can you replace a critical library with an adversarial version?',

    
    'a09_01' => 'Silence is golden for attackers. Find a high-priority action that the system fails to log, allowing you to escalate privileges silently.',
    'a09_02' => 'Weak logging practices often omit critical context. Identify an action that is logged poorly and use the lack of trail to your advantage.',
    'a09_03' => 'If you can edit the logs, you can hide your tracks. Tamper with the log entries to deceive the security analysts.',
    'a09_04' => 'Alerting thresholds are meant to catch spikes. Can you bypass the alerts by crafting a low-and-slow attack that stays under the radar?',
    'a09_05' => 'Conduct a blind activity attack where your presence is invisible to standard infrastructure monitoring tools.',
    'a09_06' => 'Logs are often viewed in a dashboard. Can you inject XSS or log-based commands into the log viewer itself via a search query?',

    
    'a10_01' => 'Unexpected inputs can cause unexpected behavior. Provide a malformed input type that the validator wasn\'t prepared to handle.',
    'a10_02' => 'Too much information is a bad thing. Induce a fatal error to see if the server leaks environment variables in its crash report.',
    'a10_03' => 'When things go wrong, systems often fail open. Induce an unhandled exception to bypass the navigation controls.',
    'a10_04' => 'Subvert the execution flow by triggering a race condition during the handling of a heavy system exception.',
    'a10_05' => 'Capture critical infrastructure flags by crashing a core microservice and reading its unmasked error output from the console.',
    'a10_06' => 'Cripple the identity verification engine by feeding it inconsistent data that breaks the logical exception processing chain.',

    'default' => 'Investigate the underlying logic of this environment to identify the security flaw and retrieve the mission flag.'
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OWASP-2025 HUB</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="app-theme-overrides.css?v=20260817">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <style>
        :root {
            --bg: #06080c;
            --surface: rgba(255, 255, 255, 0.02);
            --border: rgba(255, 255, 255, 0.08);
            --accent: #c0151a;
            --accent-glow: rgba(192, 21, 26, 0.25);
            --text: #f8fafc;
            --text-muted: #94a3b8;
            --radius: 14px;
            --font-main: 'Plus Jakarta Sans', sans-serif;
            --font-heading: 'Space Grotesk', sans-serif;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background-color: var(--bg);
            background-image: 
                radial-gradient(circle at 50% -20%, #1a0505 0%, var(--bg) 100%),
                repeating-linear-gradient(0deg, rgba(255,255,255,0.01) 0px, rgba(255,255,255,0.01) 1px, transparent 1px, transparent 2px);
            background-size: 100% 100%, 100% 4px;
            color: var(--text);
            font-family: var(--font-main);
            overflow-x: hidden;
            padding-bottom: 50px;
        }

        .container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
        header { padding: 60px 0; text-align: center; }
        .hub-title {
            font-family: var(--font-heading);
            font-size: 3rem;
            font-weight: 800;
            text-transform: uppercase;
            background: linear-gradient(to right, #fff, var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }
        .hub-subtitle { color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.3em; font-weight: 700; }
        
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; }
        .card {
            aspect-ratio: 1 / 1;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            transition: all 0.25s;
            cursor: pointer;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .card:hover { border-color: var(--accent); background: rgba(255, 255, 255, 0.05); transform: translateY(-5px); }
        .card i { font-size: 2.5rem; margin-bottom: 20px; color: var(--accent); }
        .vuln-name { font-size: 1rem; font-weight: 700; color: #fff; line-height: 1.4; }

        #hubPage, #detailPage { transition: opacity 0.3s ease; }
        #detailPage { display: none; padding-top: 40px; }
        .back-link { display: inline-flex; align-items: center; gap: 10px; color: var(--text-muted); text-decoration: none; margin-bottom: 30px; font-weight: 700; cursor: pointer; }
        .back-link:hover { color: var(--accent); }
        
        .mission-panel { background: rgba(13, 17, 23, 0.8); border: 1px solid var(--border); border-radius: 20px; padding: 40px; margin-bottom: 30px; }
        .mission-title { font-family: var(--font-heading); font-size: 2.2rem; font-weight: 800; margin-bottom: 20px; color: #fff; }
        .doc-section { margin-bottom: 20px; }
        .doc-label { font-size: 0.75rem; font-weight: 800; color: var(--accent); text-transform: uppercase; display: block; margin-bottom: 5px; }
        .doc-text { color: var(--text-muted); font-size: 0.95rem; }

        .challenge-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 30px; }
        .level-col { background: rgba(0,0,0,0.2); border-radius: 12px; padding: 20px; }
        .level-label { font-size: 0.75rem; font-weight: 800; text-transform: uppercase; margin-bottom: 15px; display: block; }
        .beg-label { color: #00ff9d; } .int-label { color: #ffcc00; } .adv-label { color: var(--accent); }
        
        .ch-card { background: #06080c; border: 1px solid var(--border); border-radius: 8px; padding: 15px; margin-bottom: 10px; cursor: pointer; transition: 0.2s; display: flex; justify-content: space-between; align-items: center; }
        .ch-card:hover { border-color: var(--accent); transform: translateX(5px); }
        .ch-card.finished { border-color: #00ff9d; color: #00ff9d; }
        
        /* Modal */
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.9); backdrop-filter: blur(10px); z-index: 1000; display: none; align-items: center; justify-content: center; padding: 20px; }
        .modal-container { width: 100%; max-width: 600px; background: #0d1117; border: 1px solid var(--accent); border-radius: 16px; overflow: hidden; }
        .modal-header { padding: 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
        .modal-body { padding: 30px; }
        .scenario-box { background: rgba(255,255,255,0.03); padding: 20px; border-radius: 8px; border-left: 4px solid var(--accent); margin-bottom: 25px; }
        .btn-action { display: inline-block; background: var(--accent); color: #fff; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 800; text-transform: uppercase; border: none; cursor: pointer; }
        .btn-lab { background: #00ff9d; color: #000; display: none; margin-top: 10px; }
        .flag-box { margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border); }
        .flag-input-group { display: flex; gap: 10px; margin-top: 10px; }
        .flag-input { flex: 1; background: #06080c; border: 1px solid var(--border); padding: 12px; color: #fff; border-radius: 6px; }
        .btn-submit { background: #fff; color: #000; padding: 0 20px; border-radius: 6px; border: none; font-weight: 800; cursor: pointer; }
    </style>
</head>
<body>

<div class="container">
    <div id="hubPage">
        <div style="padding-top:28px;">
            <a href="dashboard.php" style="display:inline-flex;align-items:center;gap:8px;color:var(--text-muted);text-decoration:none;font-size:0.82rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;transition:color 0.2s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text-muted)'">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
        <header>
            <h1 class="hub-title">OWASP-2025 HUB</h1>
            <p class="hub-subtitle">Enterprise Discovery Platform</p>
        </header>
        <div class="grid">
            <?php foreach ($categories as $cat): ?>
                <div class="card" onclick="showDetail(<?= $cat['id'] ?>)">
                    <i class="fa-solid <?= $cat['icon'] ?>"></i>
                    <span class="vuln-name" style="display:block;"><?= $cat['title'] ?></span>
                    <span style="font-size:0.6rem; color:var(--text-muted); opacity:0.5;"><?= $cat['category'] ?> MODULE</span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="detailPage">
        <div class="back-link" onclick="showHub()"><i class="fa-solid fa-arrow-left"></i> ESCAPE TO HUB</div>
        <div id="detailContent"></div>
    </div>
</div>

<div class="modal-overlay" id="modalOverlay" onclick="closeModal(event)">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 id="modalTitle">Mission Name</h3>
            <button style="background:none; border:none; color:#fff;" onclick="document.getElementById('modalOverlay').style.display='none'"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <div class="scenario-box">
                <span class="doc-label"><i class="fa-solid fa-briefcase"></i> Mission Scenario</span>
                <p id="modalScenario" style="font-size:0.95rem; line-height:1.6; color:var(--text-muted);"></p>
            </div>
            <button class="btn-action" id="btnStart" onclick="startChallenge()">Initial Access</button>
            <a href="#" id="btnAccessLab" class="btn-action btn-lab" target="_blank">Access Lab Professional</a>
            
            <div class="flag-box">
                <span id="flagStatus" style="font-size:0.75rem; font-weight:800; color:var(--text-muted); text-transform:uppercase;">Flag Validation</span>
                <div class="flag-input-group">
                    <input type="text" id="flagInput" class="flag-input" placeholder="FLAG{...}">
                    <button class="btn-submit" onclick="submitFlag()">Check</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const categories = <?= json_encode($categories) ?>;
const docs = <?= json_encode($docs) ?>;
const flags = <?= json_encode($flags) ?>;
const scenarios = <?= json_encode($scenarios) ?>;

function getChallengesList(title) {
    const p = 'owasp_lab/';
    if (title.includes('Broken Access Control')) return {
        'Beginner': [['Document Viewer', p+'broken-access/beginner/document-viewer/index.php', 'a01_01'], ['Profile Access', p+'broken-access/beginner/profile-access/index.php', 'a01_02']],
        'Intermediate': [['ID Guessing API', p+'broken-access/intermediate/id-guessing-api/index.php', 'a01_03'], ['Admin Panel Bypass', p+'broken-access/intermediate/admin-panel-bypass/index.php', 'a01_04']],
        'Advanced': [['Multi-Role Bypass', p+'broken-access/advanced/multi-role-bypass/index.php', 'a01_05'], ['Horizontal Privilege Leak', p+'broken-access/advanced/horizontal-privilege-leak/index.php', 'a01_06']]
    };
    if (title.includes('Security Misconfiguration')) return {
        'Beginner': [['Exposed Config', p+'security-misconfig/beginner/exposed-config/index.php', 'a02_01'], ['Debug Panel', p+'security-misconfig/beginner/debug-panel/index.php', 'a02_02']],
        'Intermediate': [['Verbose Errors', p+'security-misconfig/intermediate/verbose-errors/index.php', 'a02_03'], ['Insecure Headers', p+'security-misconfig/intermediate/insecure-headers/index.php', 'a02_04']],
        'Advanced': [['Backup Exposure', p+'security-misconfig/advanced/backup-exposure/index.php', 'a02_05'], ['Admin Misroute', p+'security-misconfig/advanced/admin-misroute/index.php', 'a02_06']]
    };
    if (title.includes('Software Supply Chain Failures')) return {
        'Beginner': [['Plugin Loader', p+'supply-chain/beginner/plugin-loader/index.php', 'a03_01'], ['Update Center', p+'supply-chain/beginner/update-center/index.php', 'a03_02']],
        'Intermediate': [['Marketplace Trust', p+'supply-chain/intermediate/marketplace-trust/index.php', 'a03_03'], ['Package Installer', p+'supply-chain/intermediate/package-installer/index.php', 'a03_04']],
        'Advanced': [['Loader Manipulation', p+'supply-chain/advanced/loader-manipulation/index.php', 'a03_05'], ['Update Signature Bypass', p+'supply-chain/advanced/update-signature-bypass/index.php', 'a03_06']]
    };
    if (title.includes('Cryptographic Failures')) return {
        'Beginner': [['Weak Hashing', p+'crypto-failures/beginner/weak-hash/index.php', 'a04_01'], ['Base64 Storage', p+'crypto-failures/beginner/base64-storage/index.php', 'a04_02']],
        'Intermediate': [['Predictable Tokens', p+'crypto-failures/intermediate/predictable-token/index.php', 'a04_03'], ['Weak Encryption', p+'crypto-failures/intermediate/weak-encryption/index.php', 'a04_04']],
        'Advanced': [['Hardcoded Keys', p+'crypto-failures/advanced/hardcoded-key/index.php', 'a04_05'], ['Broken Signatures', p+'crypto-failures/advanced/broken-signature/index.php', 'a04_06']]
    };
    if (title.includes('Injection')) return {
        'Beginner': [['Employee Search', p+'injection/beginner/employee-search/index.php', 'a05_01'], ['Report Viewer', p+'injection/beginner/report-viewer/index.php', 'a05_02']],
        'Intermediate': [['Filter Dashboard', p+'injection/intermediate/filter-dashboard/index.php', 'a05_03'], ['Log Query Engine', p+'injection/intermediate/log-query-engine/index.php', 'a05_04']],
        'Advanced': [['Chained Injection API', p+'injection/advanced/chained-injection-api/index.php', 'a05_05'], ['Dynamic Query Builder', p+'injection/advanced/dynamic-query-builder/index.php', 'a05_06']]
    };
    if (title.includes('Insecure Design')) return {
        'Beginner': [['Approval Flow', p+'insecure-design/beginner/approval-flow/index.php', 'a06_01'], ['Reset Workflow', p+'insecure-design/beginner/reset-workflow/index.php', 'a06_02']],
        'Intermediate': [['Discount Engine', p+'insecure-design/intermediate/discount-engine/index.php', 'a06_03'], ['Feature Unlock', p+'insecure-design/intermediate/feature-unlock/index.php', 'a06_04']],
        'Advanced': [['Multi-Step Bypass', p+'insecure-design/advanced/multi-step-bypass/index.php', 'a06_05'], ['Role Confusion Flow', p+'insecure-design/advanced/role-confusion-flow/index.php', 'a06_06']]
    };
    if (title.includes('Authentication Failures')) return {
        'Beginner': [['Weak Login', p+'authentication-failures/beginner/weak-login/index.php', 'a07_01'], ['User Enumeration', p+'authentication-failures/beginner/user-enumeration/index.php', 'a07_02']],
        'Intermediate': [['Weak Session', p+'authentication-failures/intermediate/weak-session/index.php', 'a07_03'], ['Reset Token', p+'authentication-failures/intermediate/reset-token/index.php', 'a07_04']],
        'Advanced': [['Session Fixation', p+'authentication-failures/advanced/session-fixation/index.php', 'a07_05'], ['Auth Bypass Chain', p+'authentication-failures/advanced/auth-bypass-chain/index.php', 'a07_06']]
    };
    if (title.includes('Software/Data Integrity Failures')) return {
        'Beginner': [['Config Loader', p+'integrity-failures/beginner/config-loader/index.php', 'a08_01'], ['File Checker', p+'integrity-failures/beginner/file-checker/index.php', 'a08_02']],
        'Intermediate': [['Update Importer', p+'integrity-failures/intermediate/update-importer/index.php', 'a08_03'], ['Data Trust Engine', p+'integrity-failures/intermediate/data-trust-engine/index.php', 'a08_04']],
        'Advanced': [['Signature Bypass', p+'integrity-failures/advanced/signature-bypass/index.php', 'a08_05'], ['Integrity Chain Break', p+'integrity-failures/advanced/integrity-chain-break/index.php', 'a08_06']]
    };
    if (title.includes('Security Logging/Alerting Failures')) return {
        'Beginner': [['Missing Logs', p+'logging-failures/beginner/missing-logs/index.php', 'a09_01'], ['Weak Logging', p+'logging-failures/beginner/weak-logging/index.php', 'a09_02']],
        'Intermediate': [['Log Tampering', p+'logging-failures/intermediate/log-tampering/index.php', 'a09_03'], ['Alert Bypass', p+'logging-failures/intermediate/alert-bypass/index.php', 'a09_04']],
        'Advanced': [['Blind Activity', p+'logging-failures/advanced/blind-activity/index.php', 'a09_05'], ['Log Injection Chain', p+'logging-failures/advanced/log-injection-chain/index.php', 'a09_06']]
    };
    if (title.includes('Mishandling of Exceptional Conditions')) return {
        'Beginner': [['Verbose Errors', p+'exception-failures/beginner/verbose-errors/index.php', 'a10_01'], ['Missing Validation', p+'exception-failures/beginner/missing-validation/index.php', 'a10_02']],
        'Intermediate': [['Improper Handling', p+'exception-failures/intermediate/improper-handling/index.php', 'a10_03'], ['Fallback Bypass', p+'exception-failures/intermediate/fallback-bypass/index.php', 'a10_04']],
        'Advanced': [['Exception Chain', p+'exception-failures/advanced/exception-chain/index.php', 'a10_05'], ['Crash Exploit', p+'exception-failures/advanced/crash-exploit/index.php', 'a10_06']]
    };
    return {'Beginner': [], 'Intermediate': [], 'Advanced': []};
}

let currentChallengeId = '';
let finishedChallenges = JSON.parse(localStorage.getItem('finishedChallenges') || '[]');

function showDetail(id) {
    const cat = categories.find(c => c.id === id);
    const doc = docs[cat.title] || { overview: 'Analysis pending.', impact: 'Critical.', remediation: 'Fix needed.', vector: 'Logic.' };
    const chals = getChallengesList(cat.title);
    
    document.getElementById('hubPage').style.display = 'none';
    const detail = document.getElementById('detailPage');
    detail.style.display = 'block';
    
    document.getElementById('detailContent').innerHTML = `
        <div class="mission-panel">
            <h1 class="mission-title">${cat.title}</h1>
            <div class="doc-section"><span class="doc-label">Overview</span><p class="doc-text">${doc.overview}</p></div>
            <div class="doc-section"><span class="doc-label">Security Impact</span><p class="doc-text">${doc.impact}</p></div>
            <div class="doc-section"><span class="doc-label">Remediation Strategy</span><p class="doc-text">${doc.remediation}</p></div>
        </div>
        <div class="challenge-grid">
            ${['Beginner', 'Intermediate', 'Advanced'].map(lvl => `
                <div class="level-col">
                    <span class="level-label ${lvl.toLowerCase().substring(0,3)}-label">${lvl} Complexity</span>
                    ${chals[lvl].map(ch => {
                        const isFinished = finishedChallenges.includes(ch[2]);
                        return `<div class="ch-card ${isFinished ? 'finished' : ''}" id="ch-${ch[2]}" onclick="openModal('${ch[2]}', '${ch[0]}', '${ch[1]}')">
                            <span>${ch[0]}</span><i class="fa-solid ${isFinished ? 'fa-check-circle' : 'fa-chevron-right'}"></i>
                        </div>`;
                    }).join('')}
                </div>
            `).join('')}
        </div>
    `;
}

function openModal(id, name, path) {
    currentChallengeId = id;
    document.getElementById('modalTitle').innerText = name;
    document.getElementById('modalScenario').innerText = scenarios[id] || scenarios['default'];
    document.getElementById('btnAccessLab').href = path;
    document.getElementById('btnStart').style.display = 'inline-block';
    document.getElementById('btnAccessLab').style.display = 'none';
    
    const input = document.getElementById('flagInput');
    const status = document.getElementById('flagStatus');
    input.value = finishedChallenges.includes(id) ? (flags[id] || '') : '';
    status.innerText = finishedChallenges.includes(id) ? 'Identity Verified' : 'Flag Validation';
    status.style.color = finishedChallenges.includes(id) ? '#00ff9d' : '';

    document.getElementById('modalOverlay').style.display = 'flex';
}

function startChallenge() {
    document.getElementById('btnStart').style.display = 'none';
    document.getElementById('btnAccessLab').style.display = 'inline-block';
}

function submitFlag() {
    const input = document.getElementById('flagInput').value.trim();
    const correct = flags[currentChallengeId];
    const status = document.getElementById('flagStatus');

    if (input === correct) {
        if (!finishedChallenges.includes(currentChallengeId)) {
            finishedChallenges.push(currentChallengeId);
            localStorage.setItem('finishedChallenges', JSON.stringify(finishedChallenges));
        }
        status.innerText = 'Correct! Mission Accomplished.';
        status.style.color = '#00ff9d';
        const card = document.getElementById('ch-' + currentChallengeId);
        if(card) { card.classList.add('finished'); card.querySelector('i').className = 'fa-solid fa-check-circle'; }
        
        confetti({
            particleCount: 150,
            spread: 80,
            origin: { y: 0.6 },
            colors: ['#00ff9d', '#ffffff', '#c0151a']
        });
    } else {
        status.innerText = 'Incorrect Flag. Try again.';
        status.style.color = '#c0151a';
    }
}

function showHub() {
    document.getElementById('detailPage').style.display = 'none';
    document.getElementById('hubPage').style.display = 'block';
}

function closeModal(e) { if (e.target.id === 'modalOverlay') e.target.style.display = 'none'; }
</script>
</body>
</html>
