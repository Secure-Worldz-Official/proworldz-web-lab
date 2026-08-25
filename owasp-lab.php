<?php
require_once 'api/auth_check.php';

$categories = [
    ['id' => 1, 'title' => 'Broken Access Control',        'category' => 'A01', 'icon' => 'fa-shield-halved', 'tag' => 'ACCESS CONTROL', 'color' => '#ff2a2f'],
    ['id' => 2, 'title' => 'Security Misconfiguration',      'category' => 'A02', 'icon' => 'fa-gears', 'tag' => 'MISCONFIG', 'color' => '#ea580c'],
    ['id' => 3, 'title' => 'Software Supply Chain Failures', 'category' => 'A03', 'icon' => 'fa-cubes', 'tag' => 'SUPPLY CHAIN', 'color' => '#3b82f6'],
    ['id' => 4, 'title' => 'Cryptographic Failures',         'category' => 'A04', 'icon' => 'fa-key', 'tag' => 'CRYPTOGRAPHY', 'color' => '#8b5cf6'],
    ['id' => 5, 'title' => 'Injection',                      'category' => 'A05', 'icon' => 'fa-terminal', 'tag' => 'INJECTION', 'color' => '#ef4444'],
    ['id' => 6, 'title' => 'Insecure Design',                'category' => 'A06', 'icon' => 'fa-pen-ruler', 'tag' => 'DESIGN FLAW', 'color' => '#f59e0b'],
    ['id' => 7, 'title' => 'Authentication Failures',        'category' => 'A07', 'icon' => 'fa-user-lock', 'tag' => 'AUTHENTICATION', 'color' => '#10b981'],
    ['id' => 8, 'title' => 'Software/Data Integrity Failures','category' => 'A08', 'icon' => 'fa-file-signature', 'tag' => 'INTEGRITY', 'color' => '#06b6d4'],
    ['id' => 9, 'title' => 'Security Logging/Alerting Failures','category' => 'A09', 'icon' => 'fa-list-check', 'tag' => 'MONITORING', 'color' => '#ec4899'],
    ['id' => 10,'title' => 'Mishandling of Exceptional Conditions','category' => 'A10', 'icon' => 'fa-triangle-exclamation', 'tag' => 'EXCEPTIONS', 'color' => '#f43f5e'],
];

$docs = [
    'Broken Access Control' => [
        'overview' => 'Failure to enforce restrictions on what authenticated users are allowed to do. Challenges include IDOR, privilege escalation, and multi-role bypass.',
        'impact' => 'Unauthorized data access, full tenant account takeover, and unintended modification of mission-critical records.',
        'remediation' => 'Implement strict server-side access control checks with principle of least privilege on every endpoint.',
        'vector' => 'Modification of URL parameters, session tokens, JWT claims, or predictable API object identifiers.'
    ],
    'Security Misconfiguration' => [
        'overview' => 'Insecure default settings, open cloud buckets, unmasked stack traces, and accessible administrative debug interfaces.',
        'impact' => 'Information disclosure of environment variables, server secrets, internal network routing, and unprotected admin endpoints.',
        'remediation' => 'Automate environment hardening, disable verbose debug modes, and implement security headers across the stack.',
        'vector' => 'Exposed .git repos, unlinked backup files, verbose error logs, or misrouted subnets.'
    ],
    'Software Supply Chain Failures' => [
        'overview' => 'Importing dependencies from untrusted sources, unverified plugin loaders, and unauthenticated automated update pipelines.',
        'impact' => 'Remote Code Execution (RCE) via malicious dependencies, backdoored packages, or compromised update servers.',
        'remediation' => 'Cryptographically verify digital signatures, enforce SBOM audits, and pin all third-party package hashes.',
        'vector' => 'Compromised package mirrors, untrusted repository registries, or manipulated loader metadata.'
    ],
    'Cryptographic Failures' => [
        'overview' => 'Exposure of sensitive data at rest or in transit due to weak encryption algorithms, hardcoded keys, or predictable RNG tokens.',
        'impact' => 'Theft of PII, financial credentials, database dumps, and cryptographic token forgery.',
        'remediation' => 'Use industry-standard authenticated encryption (AES-256-GCM), secure KMS key vaults, and SHA-256/Argon2id hashing.',
        'vector' => 'Weak MD5/SHA1 hashes, Base64 obfuscation treated as encryption, or leaked static AES keys.'
    ],
    'Injection' => [
        'overview' => 'Untrusted user data sent directly to an interpreter as part of a SQL query, system command, LDAP lookup, or log stream.',
        'impact' => 'Complete database exfiltration, host operating system compromise, log tampering, and administrative bypass.',
        'remediation' => 'Mandate parameterized queries, ORM prepared statements, and strict context-aware output encoding.',
        'vector' => 'Concatenation of raw user input into SQL queries, CLI commands, or dynamic query templates.'
    ],
    'Insecure Design' => [
        'overview' => 'Inherent flaws in the architectural design of business logic, transaction workflows, and rate-limiting controls.',
        'impact' => 'Bypassing payment gateways, abusing unlimited promotional discounts, and manipulating multi-step state machines.',
        'remediation' => 'Perform threat modeling during architecture phases and validate all business invariants strictly server-side.',
        'vector' => 'Skipping multi-factor verification steps, altering shopping cart quantities, or race condition abuse.'
    ],
    'Authentication Failures' => [
        'overview' => 'Insecure identity management, credential stuffing vulnerability, weak session entropy, and predictable password reset mechanisms.',
        'impact' => 'Arbitrary account takeover, persistent session hijacking, and brute-force access to privileged credentials.',
        'remediation' => 'Deploy multi-factor authentication (MFA), cryptographically secure session IDs, and rate-limited auth endpoints.',
        'vector' => 'Credential enumeration via verbose responses, session fixation, or predictable reset tokens.'
    ],
    'Software/Data Integrity Failures' => [
        'overview' => 'Assumptions made about data streams, CI/CD pipelines, and software updates without cryptographically verifying integrity.',
        'impact' => 'Arbitrary code execution via tampered serialized objects, malicious firmware updates, or poisoned configuration states.',
        'remediation' => 'Implement cryptographic signing and verification for all serialized payloads, updates, and configuration streams.',
        'vector' => 'Forging unverified digital signatures, intercepting plaintext update channels, or tampering with serialized data.'
    ],
    'Security Logging/Alerting Failures' => [
        'overview' => 'Inability to detect, monitor, and respond to breaches in real time due to missing or inadequate security telemetry.',
        'impact' => 'Prolonged attacker dwell time, silent privilege escalation, and lack of forensic evidence during breach investigations.',
        'remediation' => 'Log all security-critical transactions with contextual metadata and route alerts to a real-time SIEM/SOC.',
        'vector' => 'Low-and-slow brute force attacks avoiding thresholds, log injection to blind monitors, or unmonitored admin actions.'
    ],
    'Mishandling of Exceptional Conditions' => [
        'overview' => 'Improper error handling that leaks internal system memory, crashes microservices into an open state, or bypasses logic guards.',
        'impact' => 'Full disclosure of server configuration, database schemas, API keys, and logic bypass via unhandled exception crashes.',
        'remediation' => 'Implement fail-closed defensive exception handling and display sanitized generic error messages to clients.',
        'vector' => 'Inducing crashes with malformed input payloads, triggering unhandled null pointer exceptions, or stack dump forcing.'
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
    'a01_01' => 'Nexora\'s document portal allows users to view public files. However, sensitive confidential notes are stored in the same repository. Can you exploit the object reference and exfiltrate the private flag?',
    'a01_02' => 'The employee profile directory retrieves user bios by ID parameter. The backend forgets to enforce authorization checks. Leak the classified flag from a restricted executive profile.',
    'a01_03' => 'The internal API microservice relies on predictable numeric identifiers. Enumerate the endpoint parameters to uncover hidden intelligence records and retrieve the secret token.',
    'a01_04' => 'A legacy management console checks for a valid session token, but omits role verification. Manipulate your request context to breach the administrative core.',
    'a01_05' => 'Role-Based Access Control rules are improperly validated across subroutines. Manipulate your session privileges to access high-clearance audit logs.',
    'a01_06' => 'Departmental reports are meant to be private, but the report renderer fails to verify owner claims. Breach horizontal boundaries and extract reports belonging to peers.',

    'a02_01' => 'A developer left a critical configuration file world-readable in the web directory. Discover the config and extract administrative master credentials.',
    'a02_02' => 'A development debug console was inadvertently left active on production. Leverage its diagnostic dumps to reveal internal system states and captured flags.',
    'a02_03' => 'Verbose exception reporting exposes internal directory structures and database queries. Trigger a controlled crash to force the server into spilling its secrets.',
    'a02_04' => 'Security headers are completely missing from the target web application. Exploit missing protection policies to harvest the secret flag.',
    'a02_05' => 'Maintenance backup archives (.bak, .old) were left lingering in the deployment root. Enumerate common archive nomenclature to extract confidential secrets.',
    'a02_06' => 'Routing rules across API gateways contain path normalization discrepancies. Craft a URL traversal payload to bypass the gateway and access the internal subnet.',

    'a03_01' => 'The DevOps hub allows loading third-party plugins from remote mirrors without origin validation. Deploy a rogue module to capture the system flag.',
    'a03_02' => 'The automated package updater pulls updates without TLS pin verification. Poison the update stream to deploy a simulated backdoor package.',
    'a03_03' => 'The marketplace registry trusts external provider metadata implicitly. Inject unauthorized code into the lifecycle through a tampered package manifest.',
    'a03_04' => 'Package reputation scores can be spoofed in the dependency resolver. Trick the package manager into loading a counterfeit module.',
    'a03_05' => 'A dynamic library loader executes scripts during initialization. Hijack the dynamic load path to redirect the execution flow.',
    'a03_06' => 'Digital signature verification logic performs weak loose comparisons. Forge an authorized signature to bypass the verification check.',

    'a04_01' => 'Nexora stores master vault credentials using an obsolete, broken hashing algorithm. Reverse the hashes to recover the plaintext administrative password.',
    'a04_02' => 'Sensitive customer tokens are obfuscated with standard Base64 encoding instead of true encryption. Decode the secret stream to capture the flag.',
    'a04_03' => 'Predictable pseudo-random number generators seed the session generator. Mathematically calculate the next sequence state to hijack administrative tokens.',
    'a04_04' => 'A custom proprietary XOR cipher was implemented by an engineer. Analyze ciphertext patterns to reverse engineer the key and decrypt the flag.',
    'a04_05' => 'Cryptographic encryption keys were accidentally hardcoded in client-accessible assets. Locate the key and decrypt the protected data store.',
    'a04_06' => 'Signature comparison vulnerabilities permit timing attacks and loose equality bypasses. Exploit validation flaws to forge valid signatures.',

    'a05_01' => 'The employee lookup query concatenates raw user strings directly into SQL statements. Inject SQL commands to dump the database and capture the flag.',
    'a05_02' => 'Financial reporting filters execute dynamic queries without parameterized sanitization. Exploit SQL injection in the department filter to view confidential payroll data.',
    'a05_03' => 'A basic web application filter attempts to block common SQL keywords like SELECT. Devise an obfuscated bypass to execute arbitrary SQL commands.',
    'a05_04' => 'Search queries are logged directly into an administrative monitoring facility. Inject newline control characters to forge audit logs and poison telemetry.',
    'a05_05' => 'User-supplied profile fields are stored cleanly but rendered dangerously in secondary workflows. Execute a second-order injection attack.',
    'a05_06' => 'The search backend constructs dynamic queries with no visible error output. Use blind boolean SQL injection techniques to extract database secrets character by character.',

    'a06_01' => 'The multi-step executive approval workflow lacks server-side state enforcement. Skip intermediate verification stages to trigger direct authorization.',
    'a06_02' => 'The password reset sequence relies on client-side state progression flags. Manipulate client state to reset administrative accounts without an OTP.',
    'a06_03' => 'The eCommerce pricing engine contains a mathematical logic flaw with negative quantities. Manipulate item counts to acquire expensive security licenses for free.',
    'a06_04' => 'Enterprise premium capabilities are gated solely by client-side JavaScript checks. Subvert the client validation to unlock restricted enterprise tools.',
    'a06_05' => 'Hardware asset allocation sequences permit parameter reordering. Manipulate request steps to assign cloud computing instances directly to your profile.',
    'a06_06' => 'Role confusion occurs when handling dual-role team invitations. Manipulate invite metadata to escalate to workspace administrator.',

    'a07_01' => 'The primary login authentication mechanism evaluates credentials with loose comparison logic. Exploit logic flaws to achieve unauthorized login.',
    'a07_02' => 'Differential timing and verbose error messages allow user enumeration. Enumerate valid executive usernames and brute force access.',
    'a07_03' => 'Session tokens are generated using low-entropy timestamp sequences. Predict active session tokens to hijack privileged accounts.',
    'a07_04' => 'Account password reset tokens are derived from predictable user properties. Calculate the reset token for the root administrator and capture the flag.',
    'a07_05' => 'The application accepts pre-set session identifiers prior to authentication. Execute a session fixation attack to capture an authenticated session.',
    'a07_06' => 'Combine multiple weak authentication primitives into an exploit chain to bypass identity access management entirely.',

    'a08_01' => 'System configuration parameters are transmitted as unverified plaintext. Tamper with settings during transit to force arbitrary behavior.',
    'a08_02' => 'The file verification engine checks file hashes, but accepts the expected checksum from client input. Upload a malicious payload with a matching fake hash.',
    'a08_03' => 'The automated build pipeline consumes packages from untrusted mirrors without verification. Inject malicious tasks into the update stream.',
    'a08_04' => 'State objects are deserialized directly from untrusted client cookies. Manipulate object properties to gain administrative rights.',
    'a08_05' => 'Code update signature chains accept self-signed certificates from arbitrary authorities. Authorize a rogue update payload.',
    'a08_06' => 'Automated deployment jobs synchronize libraries from central repositories. Replace a mission-critical library with an adversarial version.',

    'a09_01' => 'Critical administrative actions fail to generate security audit trails. Execute silent privilege elevation without triggering monitoring alerts.',
    'a09_02' => 'Security logging omits source IP and timestamp attribution. Exploit telemetry gaps to carry out undetected recon operations.',
    'a09_03' => 'The log ingestion API permits write-access from unauthenticated network nodes. Tamper with log entries to disguise malicious activity.',
    'a09_04' => 'Real-time alert thresholds are triggered only by high-frequency volume spikes. Craft a low-frequency attack that stays under SIEM detection thresholds.',
    'a09_05' => 'Execute actions that evade SIEM correlation rules completely to achieve full objective completion undetected.',
    'a09_06' => 'Audit logs are rendered in an HTML dashboard without sanitization. Inject payload strings into search queries to achieve XSS in the log viewer.',

    'a10_01' => 'The input parser throws an unhandled exception when receiving unexpected data types, leaking internal server environment paths.',
    'a10_02' => 'Unhandled exceptions cause the application to dump full execution stack traces containing database passwords and API tokens.',
    'a10_03' => 'When internal services fail, the routing gateway fails open rather than closed. Trigger service crashes to bypass access control gates.',
    'a10_04' => 'Heavy exception processing introduces race conditions in session validation routines. Exploit the race window to bypass authentication.',
    'a10_05' => 'Crash a core microservice to force the orchestrator to dump unmasked configuration states into the public diagnostic stream.',
    'a10_06' => 'Provide contradictory multi-parameter payloads to break the exception handler and extract privileged mission flags.',

    'default' => 'Investigate the underlying application architecture to locate the security vulnerability, exploit the flaw, and extract the authorization flag.'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OWASP 2025 Cyber Range | Secure Worldz Academy</title>
    <link rel="icon" type="image/webp" href="image.webp">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="app-theme-overrides.css?v=20260823">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <style>
        :root {
            --bg-base: #030508;
            --bg-elevated: #070a10;
            --bg-card: rgba(13, 17, 26, 0.75);
            --bg-card-hover: rgba(20, 27, 40, 0.9);
            --border-dim: rgba(255, 255, 255, 0.08);
            --border-glow: rgba(255, 42, 47, 0.4);
            --primary: #ff2a2f;
            --primary-glow: rgba(255, 42, 47, 0.25);
            --emerald: #10b981;
            --emerald-glow: rgba(16, 185, 129, 0.25);
            --amber: #f59e0b;
            --amber-glow: rgba(245, 158, 11, 0.25);
            --cyan: #06b6d4;
            --text-main: #f8fafc;
            --text-secondary: #94a3b8;
            --text-dim: #64748b;
            --font-heading: 'Space Grotesk', sans-serif;
            --font-body: 'Plus Jakarta Sans', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --radius-lg: 16px;
            --radius-md: 12px;
            --radius-sm: 8px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
        }

        body {
            background-color: var(--bg-base);
            background-image: 
                radial-gradient(circle at 50% -10%, rgba(255, 42, 47, 0.12) 0%, transparent 60%),
                radial-gradient(circle at 90% 40%, rgba(59, 130, 246, 0.05) 0%, transparent 50%),
                repeating-linear-gradient(0deg, rgba(255, 255, 255, 0.012) 0px, rgba(255, 255, 255, 0.012) 1px, transparent 1px, transparent 3px);
            color: var(--text-main);
            font-family: var(--font-body);
            min-height: 100vh;
            overflow-x: hidden;
            padding-bottom: 80px;
        }

        .range-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* ===== TOP HUD / TELEMETRY BAR ===== */
        .hud-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px 0 16px;
            border-bottom: 1px solid var(--border-dim);
            gap: 16px;
            flex-wrap: wrap;
        }

        .hud-back {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--text-secondary);
            text-decoration: none;
            font-family: var(--font-mono);
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 8px 16px;
            border-radius: var(--radius-sm);
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-dim);
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .hud-back:hover {
            color: #fff;
            border-color: var(--primary);
            background: var(--primary-glow);
            transform: translateX(-3px);
        }

        .hud-live-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 999px;
            background: rgba(16, 185, 129, 0.08);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #6ee7b7;
            font-family: var(--font-mono);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .live-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 10px #10b981;
            animation: radarPulse 2s infinite ease-in-out;
        }

        @keyframes radarPulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(1.3); }
        }

        /* ===== HERO HEADER ===== */
        .range-hero {
            padding: 40px 0 28px;
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 32px;
            align-items: center;
        }

        .hero-title-group .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary);
            font-family: var(--font-mono);
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .hero-title {
            font-family: var(--font-heading);
            font-size: clamp(2.4rem, 4.5vw, 3.8rem);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.03em;
            background: linear-gradient(135deg, #ffffff 30%, #fca5a5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 14px;
        }

        .hero-desc {
            color: var(--text-secondary);
            font-size: 1rem;
            line-height: 1.7;
            max-width: 620px;
        }

        /* Telemetry Stats Card */
        .telemetry-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-dim);
            border-radius: var(--radius-lg);
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 18px;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.4);
            position: relative;
            overflow: hidden;
        }

        .telemetry-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--primary), var(--cyan), transparent);
        }

        .telemetry-stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
        }

        .t-stat {
            background: rgba(255, 255, 255, 0.025);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: var(--radius-md);
            padding: 12px 14px;
            text-align: center;
        }

        .t-stat-label {
            display: block;
            font-family: var(--font-mono);
            font-size: 0.65rem;
            font-weight: 800;
            color: var(--text-dim);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .t-stat-val {
            font-family: var(--font-heading);
            font-size: 1.4rem;
            font-weight: 800;
            color: #fff;
        }

        .t-stat-val.highlight {
            color: var(--primary);
            text-shadow: 0 0 16px var(--primary-glow);
        }

        /* Progress Bar */
        .telemetry-progress-wrap {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: var(--font-mono);
            font-size: 0.72rem;
            font-weight: 700;
        }

        .progress-header span:first-child { color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.08em; }
        .progress-header span:last-child { color: #fff; font-weight: 800; }

        .progress-bar-track {
            height: 8px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 999px;
            overflow: hidden;
            position: relative;
        }

        .progress-bar-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #ff2a2f 0%, #10b981 100%);
            border-radius: 999px;
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 0 12px rgba(255, 42, 47, 0.5);
        }

        /* ===== CONTROLS / FILTER BAR ===== */
        .range-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin: 20px 0 28px;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            flex: 1;
            max-width: 420px;
            min-width: 260px;
        }

        .search-box i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-dim);
            font-size: 0.9rem;
        }

        .search-input {
            width: 100%;
            background: var(--bg-card);
            border: 1px solid var(--border-dim);
            border-radius: var(--radius-md);
            padding: 12px 16px 12px 44px;
            color: #fff;
            font-family: var(--font-body);
            font-size: 0.88rem;
            outline: none;
            transition: all 0.2s ease;
        }

        .search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 16px var(--primary-glow);
            background: rgba(20, 27, 40, 0.95);
        }

        .filter-pills {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .filter-btn {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-dim);
            color: var(--text-secondary);
            padding: 8px 14px;
            border-radius: var(--radius-sm);
            font-family: var(--font-mono);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
            box-shadow: 0 0 14px var(--primary-glow);
        }

        /* ===== MODULE GRID ===== */
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
        }

        .module-card {
            background: linear-gradient(160deg, rgba(22, 28, 42, 0.75) 0%, rgba(10, 14, 22, 0.85) 100%);
            border: 1px solid var(--border-dim);
            border-radius: var(--radius-lg);
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 20px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        .module-card::after {
            content: '';
            position: absolute;
            inset: auto -40px -40px auto;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: radial-gradient(circle, var(--primary-glow), transparent 70%);
            opacity: 0.5;
            pointer-events: none;
            transition: transform 0.4s ease, opacity 0.4s ease;
        }

        .module-card:hover {
            transform: translateY(-6px);
            border-color: var(--border-glow);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.7), 0 0 25px -5px var(--primary-glow);
            background: linear-gradient(160deg, rgba(28, 36, 54, 0.9) 0%, rgba(14, 18, 28, 0.95) 100%);
        }

        .module-card:hover::after {
            transform: scale(1.4);
            opacity: 0.8;
        }

        .m-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .m-code-badge {
            font-family: var(--font-mono);
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            color: #fca5a5;
            background: rgba(255, 42, 47, 0.1);
            border: 1px solid rgba(255, 42, 47, 0.3);
            padding: 4px 10px;
            border-radius: 6px;
        }

        .m-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: var(--font-mono);
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #86efac;
            background: rgba(16, 185, 129, 0.08);
            border: 1px solid rgba(16, 185, 129, 0.25);
            padding: 4px 8px;
            border-radius: 999px;
        }

        .m-icon-wrap {
            width: 52px;
            height: 52px;
            border-radius: var(--radius-md);
            background: rgba(255, 42, 47, 0.08);
            border: 1px solid rgba(255, 42, 47, 0.25);
            display: grid;
            place-items: center;
            font-size: 1.35rem;
            color: #fca5a5;
            box-shadow: inset 0 0 15px rgba(255, 42, 47, 0.1);
            transition: transform 0.3s ease;
        }

        .module-card:hover .m-icon-wrap {
            transform: scale(1.1) rotate(4deg);
            background: rgba(255, 42, 47, 0.15);
            border-color: var(--primary);
            color: #fff;
        }

        .m-title {
            font-family: var(--font-heading);
            font-size: 1.25rem;
            font-weight: 700;
            line-height: 1.3;
            color: #fff;
            margin-bottom: 8px;
        }

        .m-desc {
            color: var(--text-secondary);
            font-size: 0.84rem;
            line-height: 1.6;
        }

        .m-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            padding-top: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .m-progress-tag {
            font-family: var(--font-mono);
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--text-dim);
        }

        .m-progress-tag span {
            color: #fff;
            font-weight: 800;
        }

        .m-enter-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: var(--font-mono);
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #fff;
            transition: transform 0.2s ease;
        }

        .module-card:hover .m-enter-btn {
            color: var(--primary);
            transform: translateX(4px);
        }

        /* ===== DETAIL PAGE / DOSSIER ===== */
        #detailPage {
            display: none;
            padding-top: 20px;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .detail-header-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .dossier-panel {
            background: linear-gradient(150deg, rgba(20, 26, 40, 0.95) 0%, rgba(9, 12, 18, 0.98) 100%);
            border: 1px solid var(--border-dim);
            border-radius: var(--radius-lg);
            padding: clamp(24px, 4vw, 36px);
            margin-bottom: 32px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
        }

        .dossier-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--primary);
            box-shadow: 0 0 16px var(--primary);
        }

        .dossier-head {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .dossier-code {
            font-family: var(--font-mono);
            font-size: 0.85rem;
            font-weight: 800;
            letter-spacing: 0.15em;
            color: var(--primary);
            background: rgba(255, 42, 47, 0.12);
            border: 1px solid rgba(255, 42, 47, 0.35);
            padding: 6px 14px;
            border-radius: 8px;
        }

        .dossier-title {
            font-family: var(--font-heading);
            font-size: clamp(1.8rem, 3.5vw, 2.5rem);
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #fff;
        }

        .dossier-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .dossier-card {
            background: rgba(255, 255, 255, 0.025);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: var(--radius-md);
            padding: 18px;
        }

        .dossier-card-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: var(--font-mono);
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #fca5a5;
            margin-bottom: 10px;
        }

        .dossier-card-text {
            color: var(--text-secondary);
            font-size: 0.84rem;
            line-height: 1.65;
        }

        /* Challenge Matrix */
        .challenge-tier-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .tier-column {
            background: rgba(12, 16, 24, 0.6);
            border: 1px solid var(--border-dim);
            border-radius: var(--radius-lg);
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .tier-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .tier-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: var(--font-mono);
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .tier-badge.beg { color: var(--emerald); }
        .tier-badge.int { color: var(--amber); }
        .tier-badge.adv { color: var(--primary); }

        .tier-points {
            font-family: var(--font-mono);
            font-size: 0.68rem;
            font-weight: 700;
            color: var(--text-dim);
        }

        .challenge-item {
            background: rgba(18, 23, 34, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: var(--radius-md);
            padding: 16px;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .challenge-item:hover {
            transform: translateX(4px);
            border-color: rgba(255, 42, 47, 0.5);
            background: rgba(255, 42, 47, 0.08);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
        }

        .challenge-item.solved {
            border-color: rgba(16, 185, 129, 0.4);
            background: rgba(16, 185, 129, 0.06);
        }

        .ch-item-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .ch-node-id {
            font-family: var(--font-mono);
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            color: var(--text-dim);
        }

        .ch-status-pill {
            font-family: var(--font-mono);
            font-size: 0.62rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 3px 8px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-secondary);
        }

        .challenge-item.solved .ch-status-pill {
            background: rgba(16, 185, 129, 0.15);
            color: #6ee7b7;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .ch-name {
            font-family: var(--font-heading);
            font-size: 0.95rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.35;
        }

        .ch-item-foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid rgba(255, 255, 255, 0.04);
            padding-top: 10px;
        }

        .ch-tag {
            font-family: var(--font-mono);
            font-size: 0.65rem;
            color: var(--text-dim);
        }

        .ch-action-icon {
            color: var(--text-dim);
            font-size: 0.8rem;
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .challenge-item:hover .ch-action-icon {
            color: var(--primary);
            transform: translateX(3px);
        }

        .challenge-item.solved .ch-action-icon {
            color: var(--emerald);
        }

        /* ===== TACTICAL MODAL ===== */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            z-index: 10000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            animation: fadeIn 0.2s ease;
        }

        .modal-container {
            width: 100%;
            max-width: 640px;
            background: #090d14;
            border: 1px solid var(--border-glow);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.8), 0 0 40px rgba(255, 42, 47, 0.2);
            position: relative;
        }

        .modal-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-dim);
            background: rgba(255, 255, 255, 0.02);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-title-group {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modal-dots {
            display: flex;
            gap: 6px;
        }

        .modal-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .modal-dot.red { background: #ff5f56; }
        .modal-dot.yellow { background: #ffbd2e; }
        .modal-dot.green { background: #27c93f; }

        .modal-title {
            font-family: var(--font-heading);
            font-size: 1.15rem;
            font-weight: 700;
            color: #fff;
        }

        .modal-close-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-dim);
            color: var(--text-secondary);
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: grid;
            place-items: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .modal-close-btn:hover {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }

        .modal-body {
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .mission-intel-box {
            background: rgba(255, 255, 255, 0.025);
            border: 1px solid var(--border-dim);
            border-left: 4px solid var(--primary);
            border-radius: var(--radius-md);
            padding: 16px 20px;
        }

        .mission-intel-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: var(--font-mono);
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #fca5a5;
            margin-bottom: 8px;
        }

        .mission-intel-text {
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.65;
        }

        .target-access-box {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid var(--border-dim);
            border-radius: var(--radius-md);
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .target-status-line {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: var(--font-mono);
            font-size: 0.72rem;
            font-weight: 700;
        }

        .target-status-line span:first-child { color: var(--text-dim); text-transform: uppercase; }
        .target-status-line span:last-child { color: #86efac; display: flex; align-items: center; gap: 6px; }

        .btn-launch-env {
            width: 100%;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            padding: 12px 20px;
            font-family: var(--font-mono);
            font-size: 0.82rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-launch-env:hover {
            filter: brightness(1.15);
            box-shadow: 0 0 20px var(--primary-glow);
            transform: translateY(-1px);
        }

        .btn-access-live {
            display: none;
            width: 100%;
            background: #10b981;
            color: #030508;
            border: none;
            border-radius: var(--radius-sm);
            padding: 12px 20px;
            font-family: var(--font-mono);
            font-size: 0.82rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-access-live:hover {
            filter: brightness(1.1);
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.4);
            transform: translateY(-1px);
        }

        /* Flag Submission Input */
        .flag-submit-box {
            border-top: 1px solid var(--border-dim);
            padding-top: 18px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .flag-status-label {
            font-family: var(--font-mono);
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-dim);
        }

        .flag-input-row {
            display: flex;
            gap: 8px;
        }

        .flag-field {
            flex: 1;
            background: rgba(0, 0, 0, 0.6);
            border: 1px solid var(--border-dim);
            border-radius: var(--radius-sm);
            padding: 12px 16px;
            color: #fff;
            font-family: var(--font-mono);
            font-size: 0.88rem;
            outline: none;
            transition: border-color 0.2s ease;
        }

        .flag-field:focus {
            border-color: var(--primary);
            box-shadow: 0 0 14px var(--primary-glow);
        }

        .btn-check-flag {
            background: #fff;
            color: #030508;
            border: none;
            border-radius: var(--radius-sm);
            padding: 0 20px;
            font-family: var(--font-mono);
            font-size: 0.82rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-check-flag:hover {
            background: #f1f5f9;
            box-shadow: 0 0 14px rgba(255, 255, 255, 0.3);
        }

        /* Responsive Breakpoints */
        @media (max-width: 1024px) {
            .range-hero { grid-template-columns: 1fr; }
            .dossier-grid { grid-template-columns: repeat(2, 1fr); }
            .challenge-tier-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 640px) {
            .telemetry-stats-grid { grid-template-columns: 1fr; }
            .dossier-grid { grid-template-columns: 1fr; }
            .range-container { padding: 0 16px; }
            .hero-title { font-size: 2rem; }
        }
    </style>
</head>
<body>

<div class="range-container">
    <!-- Top HUD Navigation -->
    <header class="hud-nav">
        <a href="dashboard.php" class="hud-back">
            <i class="fa-solid fa-arrow-left"></i> ESC TO DASHBOARD
        </a>

        <div class="hud-live-pill">
            <div class="live-dot"></div>
            <span>CYBER RANGE STATUS // ONLINE</span>
        </div>
    </header>

    <!-- HUB VIEW -->
    <div id="hubPage">
        <!-- Hero Section -->
        <section class="range-hero">
            <div class="hero-title-group">
                <span class="hero-eyebrow">
                    <i class="fa-solid fa-shield-halved"></i> SECURE WORLDZ ACADEMY · LIVE RANGE
                </span>
                <h1 class="hero-title">OWASP 2025 HUB</h1>
                <p class="hero-desc">
                    Enterprise vulnerability training environment covering all 10 OWASP 2025 categories. Select an attack sector to inspect classified threat dossiers and launch interactive exploit labs.
                </p>
            </div>

            <!-- Telemetry Overview Card -->
            <div class="telemetry-card">
                <div class="telemetry-stats-grid">
                    <div class="t-stat">
                        <span class="t-stat-label">TOTAL NODES</span>
                        <span class="t-stat-val">60</span>
                    </div>
                    <div class="t-stat">
                        <span class="t-stat-label">EXPLOITED</span>
                        <span class="t-stat-val highlight" id="globalSolvedCount">0</span>
                    </div>
                    <div class="t-stat">
                        <span class="t-stat-label">PTS EARNED</span>
                        <span class="t-stat-val" id="globalPointsCount">0</span>
                    </div>
                </div>

                <div class="telemetry-progress-wrap">
                    <div class="progress-header">
                        <span>Range Completion</span>
                        <span id="globalProgressPct">0%</span>
                    </div>
                    <div class="progress-bar-track">
                        <div class="progress-bar-fill" id="globalProgressBar"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Controls: Search and Filters -->
        <div class="range-controls">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="moduleSearch" class="search-input" placeholder="Search vulnerability module or ID..." oninput="filterModules()">
            </div>

            <div class="filter-pills">
                <button class="filter-btn active" onclick="setCategoryFilter('ALL', this)">ALL (10)</button>
                <button class="filter-btn" onclick="setCategoryFilter('ACCESS', this)">ACCESS</button>
                <button class="filter-btn" onclick="setCategoryFilter('INJECTION', this)">INJECTION</button>
                <button class="filter-btn" onclick="setCategoryFilter('CRYPTO', this)">CRYPTO</button>
                <button class="filter-btn" onclick="setCategoryFilter('SUPPLY', this)">SUPPLY CHAIN</button>
            </div>
        </div>

        <!-- Module Grid -->
        <div class="modules-grid" id="modulesGrid">
            <?php foreach ($categories as $cat): ?>
                <div class="module-card" data-category-id="<?= $cat['id'] ?>" data-tag="<?= htmlspecialchars($cat['tag']) ?>" data-title="<?= htmlspecialchars($cat['title']) ?>" onclick="showDetail(<?= $cat['id'] ?>)" role="button" tabindex="0" onkeydown="if(event.key === 'Enter' || event.key === ' '){ event.preventDefault(); showDetail(<?= $cat['id'] ?>); }">
                    <div>
                        <div class="m-top">
                            <span class="m-code-badge"><?= $cat['category'] ?> // SECTOR</span>
                            <span class="m-status"><i class="fa-solid fa-circle-check"></i> ACTIVE</span>
                        </div>
                        
                        <div style="margin: 18px 0 14px;">
                            <div class="m-icon-wrap">
                                <i class="fa-solid <?= $cat['icon'] ?>"></i>
                            </div>
                        </div>

                        <h3 class="m-title"><?= htmlspecialchars($cat['title']) ?></h3>
                        <p class="m-desc">6 combat scenarios across Beginner, Intermediate, and Advanced tiers.</p>
                    </div>

                    <div class="m-footer">
                        <span class="m-progress-tag">COMPLETED: <span id="mod-progress-<?= $cat['category'] ?>">0/6</span></span>
                        <span class="m-enter-btn">ENGAGE SECTOR <i class="fa-solid fa-arrow-right"></i></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- DETAIL VIEW / DOSSIER -->
    <div id="detailPage">
        <div class="detail-header-nav">
            <button class="hud-back" onclick="showHub()">
                <i class="fa-solid fa-arrow-left"></i> RETURN TO SECTOR GRID
            </button>

            <span class="hud-live-pill">
                <i class="fa-solid fa-crosshairs"></i> TARGET SECTOR ACQUIRED
            </span>
        </div>

        <div id="detailContent"></div>
    </div>
</div>

<!-- TACTICAL MISSION EXECUTION MODAL -->
<div class="modal-overlay" id="modalOverlay" onclick="closeModal(event)">
    <div class="modal-container" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div class="modal-title-group">
                <div class="modal-dots">
                    <div class="modal-dot red"></div>
                    <div class="modal-dot yellow"></div>
                    <div class="modal-dot green"></div>
                </div>
                <h3 class="modal-title" id="modalTitle">Mission Target</h3>
            </div>
            <button class="modal-close-btn" onclick="document.getElementById('modalOverlay').style.display='none'">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="modal-body">
            <div class="mission-intel-box">
                <span class="mission-intel-label"><i class="fa-solid fa-terminal"></i> THREAT INTELLIGENCE & BRIEFING</span>
                <p class="mission-intel-text" id="modalScenario"></p>
            </div>

            <div class="target-access-box">
                <div class="target-status-line">
                    <span>CONTAINER STATE</span>
                    <span><div class="live-dot" style="background:#10b981;"></div> READY FOR ATTACK</span>
                </div>
                <button class="btn-launch-env" id="btnStart" onclick="startChallenge()">
                    <i class="fa-solid fa-bolt"></i> INITIALIZE ATTACK ENVIRONMENT
                </button>
                <a href="#" id="btnAccessLab" class="btn-access-live" target="_blank">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> ACCESS TARGET LAB INSTANCE
                </a>
            </div>

            <div class="flag-submit-box">
                <span class="flag-status-label" id="flagStatus">FLAG VALIDATION CONSOLE</span>
                <div class="flag-input-row">
                    <input type="text" id="flagInput" class="flag-field" placeholder="FLAG{...}" autocomplete="off" spellcheck="false" onkeydown="if(event.key === 'Enter'){ submitFlag(); }">
                    <button class="btn-check-flag" onclick="submitFlag()">TRANSMIT</button>
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
        'Beginner': [['Document Viewer', p+'broken-access/beginner/document-viewer/index.php', 'a01_01', 'IDOR Bypass', 100], ['Profile Access', p+'broken-access/beginner/profile-access/index.php', 'a01_02', 'Profile Leak', 100]],
        'Intermediate': [['ID Guessing API', p+'broken-access/intermediate/id-guessing-api/index.php', 'a01_03', 'API Enumeration', 250], ['Admin Panel Bypass', p+'broken-access/intermediate/admin-panel-bypass/index.php', 'a01_04', 'Role Bypass', 250]],
        'Advanced': [['Multi-Role Bypass', p+'broken-access/advanced/multi-role-bypass/index.php', 'a01_05', 'RBAC Elevation', 500], ['Horizontal Privilege Leak', p+'broken-access/advanced/horizontal-privilege-leak/index.php', 'a01_06', 'Data Exfiltration', 500]]
    };
    if (title.includes('Security Misconfiguration')) return {
        'Beginner': [['Exposed Config', p+'security-misconfig/beginner/exposed-config/index.php', 'a02_01', 'Config Exposure', 100], ['Debug Panel', p+'security-misconfig/beginner/debug-panel/index.php', 'a02_02', 'Debug Leak', 100]],
        'Intermediate': [['Verbose Errors', p+'security-misconfig/intermediate/verbose-errors/index.php', 'a02_03', 'Stack Dump', 250], ['Insecure Headers', p+'security-misconfig/intermediate/insecure-headers/index.php', 'a02_04', 'Policy Flaw', 250]],
        'Advanced': [['Backup Exposure', p+'security-misconfig/advanced/backup-exposure/index.php', 'a02_05', 'Backup Extraction', 500], ['Admin Misroute', p+'security-misconfig/advanced/admin-misroute/index.php', 'a02_06', 'Routing Flaw', 500]]
    };
    if (title.includes('Software Supply Chain Failures')) return {
        'Beginner': [['Plugin Loader', p+'supply-chain/beginner/plugin-loader/index.php', 'a03_01', 'Plugin Injection', 100], ['Update Center', p+'supply-chain/beginner/update-center/index.php', 'a03_02', 'Update Poisoning', 100]],
        'Intermediate': [['Marketplace Trust', p+'supply-chain/intermediate/marketplace-trust/index.php', 'a03_03', 'Provider Spoof', 250], ['Package Installer', p+'supply-chain/intermediate/package-installer/index.php', 'a03_04', 'Package Tamper', 250]],
        'Advanced': [['Loader Manipulation', p+'supply-chain/advanced/loader-manipulation/index.php', 'a03_05', 'Dynamic Load Hijack', 500], ['Update Signature Bypass', p+'supply-chain/advanced/update-signature-bypass/index.php', 'a03_06', 'Signature Forge', 500]]
    };
    if (title.includes('Cryptographic Failures')) return {
        'Beginner': [['Weak Hashing', p+'crypto-failures/beginner/weak-hash/index.php', 'a04_01', 'Hash Reversal', 100], ['Base64 Storage', p+'crypto-failures/beginner/base64-storage/index.php', 'a04_02', 'Encoding Flaw', 100]],
        'Intermediate': [['Predictable Tokens', p+'crypto-failures/intermediate/predictable-token/index.php', 'a04_03', 'PRNG Predict', 250], ['Weak Encryption', p+'crypto-failures/intermediate/weak-encryption/index.php', 'a04_04', 'Cipher Break', 250]],
        'Advanced': [['Hardcoded Keys', p+'crypto-failures/advanced/hardcoded-key/index.php', 'a04_05', 'Key Extraction', 500], ['Broken Signatures', p+'crypto-failures/advanced/broken-signature/index.php', 'a04_06', 'Signature Bypass', 500]]
    };
    if (title.includes('Injection')) return {
        'Beginner': [['Employee Search', p+'injection/beginner/employee-search/index.php', 'a05_01', 'Direct SQLi', 100], ['Report Viewer', p+'injection/beginner/report-viewer/index.php', 'a05_02', 'Filter SQLi', 100]],
        'Intermediate': [['Filter Dashboard', p+'injection/intermediate/filter-dashboard/index.php', 'a05_03', 'WAF Filter Bypass', 250], ['Log Query Engine', p+'injection/intermediate/log-query-engine/index.php', 'a05_04', 'Log Injection', 250]],
        'Advanced': [['Chained Injection API', p+'injection/advanced/chained-injection-api/index.php', 'a05_05', 'Second-Order SQLi', 500], ['Dynamic Query Builder', p+'injection/advanced/dynamic-query-builder/index.php', 'a05_06', 'Blind Boolean SQLi', 500]]
    };
    if (title.includes('Insecure Design')) return {
        'Beginner': [['Approval Flow', p+'insecure-design/beginner/approval-flow/index.php', 'a06_01', 'Workflow Skip', 100], ['Reset Workflow', p+'insecure-design/beginner/reset-workflow/index.php', 'a06_02', 'State Flaw', 100]],
        'Intermediate': [['Discount Engine', p+'insecure-design/intermediate/discount-engine/index.php', 'a06_03', 'Quantity Logic Abuse', 250], ['Feature Unlock', p+'insecure-design/intermediate/feature-unlock/index.php', 'a06_04', 'Client Guard Bypass', 250]],
        'Advanced': [['Multi-Step Bypass', p+'insecure-design/advanced/multi-step-bypass/index.php', 'a06_05', 'Step Manipulation', 500], ['Role Confusion Flow', p+'insecure-design/advanced/role-confusion-flow/index.php', 'a06_06', 'Role Confusion', 500]]
    };
    if (title.includes('Authentication Failures')) return {
        'Beginner': [['Weak Login', p+'authentication-failures/beginner/weak-login/index.php', 'a07_01', 'Logic Bypass', 100], ['User Enumeration', p+'authentication-failures/beginner/user-enumeration/index.php', 'a07_02', 'User Enumeration', 100]],
        'Intermediate': [['Weak Session', p+'authentication-failures/intermediate/weak-session/index.php', 'a07_03', 'Session Hijack', 250], ['Reset Token', p+'authentication-failures/intermediate/reset-token/index.php', 'a07_04', 'Token Predict', 250]],
        'Advanced': [['Session Fixation', p+'authentication-failures/advanced/session-fixation/index.php', 'a07_05', 'Session Fixation', 500], ['Auth Bypass Chain', p+'authentication-failures/advanced/auth-bypass-chain/index.php', 'a07_06', 'Full Auth Chain', 500]]
    };
    if (title.includes('Software/Data Integrity Failures')) return {
        'Beginner': [['Config Loader', p+'integrity-failures/beginner/config-loader/index.php', 'a08_01', 'Config Tamper', 100], ['File Checker', p+'integrity-failures/beginner/file-checker/index.php', 'a08_02', 'Hash Bypass', 100]],
        'Intermediate': [['Update Importer', p+'integrity-failures/intermediate/update-importer/index.php', 'a08_03', 'Update Injection', 250], ['Data Trust Engine', p+'integrity-failures/intermediate/data-trust-engine/index.php', 'a08_04', 'Object Injection', 250]],
        'Advanced': [['Signature Bypass', p+'integrity-failures/advanced/signature-bypass/index.php', 'a08_05', 'Cert Authority Bypass', 500], ['Integrity Chain Break', p+'integrity-failures/advanced/integrity-chain-break/index.php', 'a08_06', 'Library Hijack', 500]]
    };
    if (title.includes('Security Logging/Alerting Failures')) return {
        'Beginner': [['Missing Logs', p+'logging-failures/beginner/missing-logs/index.php', 'a09_01', 'Silent Escalation', 100], ['Weak Logging', p+'logging-failures/beginner/weak-logging/index.php', 'a09_02', 'Context Loss', 100]],
        'Intermediate': [['Log Tampering', p+'logging-failures/intermediate/log-tampering/index.php', 'a09_03', 'Log Rewrite', 250], ['Alert Bypass', p+'logging-failures/intermediate/alert-bypass/index.php', 'a09_04', 'Threshold Evasion', 250]],
        'Advanced': [['Blind Activity', p+'logging-failures/advanced/blind-activity/index.php', 'a09_05', 'SIEM Blind Spot', 500], ['Log Injection Chain', p+'logging-failures/advanced/log-injection-chain/index.php', 'a09_06', 'Log Dashboard XSS', 500]]
    };
    if (title.includes('Mishandling of Exceptional Conditions')) return {
        'Beginner': [['Verbose Errors', p+'exception-failures/beginner/verbose-errors/index.php', 'a10_01', 'Stack Trace Leak', 100], ['Missing Validation', p+'exception-failures/beginner/missing-validation/index.php', 'a10_02', 'Type Crash Leak', 100]],
        'Intermediate': [['Improper Handling', p+'exception-failures/intermediate/improper-handling/index.php', 'a10_03', 'Fail-Open Bypass', 250], ['Fallback Bypass', p+'exception-failures/intermediate/fallback-bypass/index.php', 'a10_04', 'Exception Race', 250]],
        'Advanced': [['Exception Chain', p+'exception-failures/advanced/exception-chain/index.php', 'a10_05', 'Service Crash Dump', 500], ['Crash Exploit', p+'exception-failures/advanced/crash-exploit/index.php', 'a10_06', 'Logic Chain Break', 500]]
    };
    return {'Beginner': [], 'Intermediate': [], 'Advanced': []};
}

let currentChallengeId = '';
let finishedChallenges = JSON.parse(localStorage.getItem('finishedChallenges') || '[]');

function updateTelemetryStats() {
    const totalCount = 60;
    const solvedCount = finishedChallenges.length;
    const pct = Math.round((solvedCount / totalCount) * 100);

    let totalPoints = 0;
    categories.forEach(cat => {
        const chals = getChallengesList(cat.title);
        ['Beginner', 'Intermediate', 'Advanced'].forEach(lvl => {
            chals[lvl].forEach(ch => {
                if (finishedChallenges.includes(ch[2])) {
                    totalPoints += (ch[4] || 100);
                }
            });
        });
    });

    document.getElementById('globalSolvedCount').textContent = solvedCount;
    document.getElementById('globalPointsCount').textContent = totalPoints;
    document.getElementById('globalProgressPct').textContent = pct + '%';
    document.getElementById('globalProgressBar').style.width = pct + '%';

    // Update individual module cards
    categories.forEach(cat => {
        const chals = getChallengesList(cat.title);
        let modSolved = 0;
        ['Beginner', 'Intermediate', 'Advanced'].forEach(lvl => {
            chals[lvl].forEach(ch => {
                if (finishedChallenges.includes(ch[2])) modSolved++;
            });
        });
        const el = document.getElementById('mod-progress-' + cat.category);
        if (el) el.textContent = modSolved + '/6';
    });
}

function showDetail(id) {
    const cat = categories.find(c => c.id === id);
    const doc = docs[cat.title] || { overview: 'Classified.', impact: 'Critical.', remediation: 'Implement least privilege.', vector: 'Logic flaws.' };
    const chals = getChallengesList(cat.title);
    
    document.getElementById('hubPage').style.display = 'none';
    const detail = document.getElementById('detailPage');
    detail.style.display = 'block';
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    document.getElementById('detailContent').innerHTML = `
        <div class="dossier-panel">
            <div class="dossier-head">
                <span class="dossier-code">${cat.category} // ATTACK SECTOR</span>
                <h1 class="dossier-title">${cat.title}</h1>
            </div>
            <div class="dossier-grid">
                <div class="dossier-card">
                    <span class="dossier-card-label"><i class="fa-solid fa-shield-halved"></i> THREAT OVERVIEW</span>
                    <p class="dossier-card-text">${doc.overview}</p>
                </div>
                <div class="dossier-card">
                    <span class="dossier-card-label"><i class="fa-solid fa-bolt"></i> ATTACK VECTOR</span>
                    <p class="dossier-card-text">${doc.vector}</p>
                </div>
                <div class="dossier-card">
                    <span class="dossier-card-label"><i class="fa-solid fa-radiation"></i> SECURITY IMPACT</span>
                    <p class="dossier-card-text">${doc.impact}</p>
                </div>
                <div class="dossier-card">
                    <span class="dossier-card-label"><i class="fa-solid fa-lock"></i> REMEDIATION</span>
                    <p class="dossier-card-text">${doc.remediation}</p>
                </div>
            </div>
        </div>

        <div class="challenge-tier-grid">
            ${['Beginner', 'Intermediate', 'Advanced'].map(lvl => {
                const cls = lvl === 'Beginner' ? 'beg' : (lvl === 'Intermediate' ? 'int' : 'adv');
                const pts = lvl === 'Beginner' ? '+100 PTS' : (lvl === 'Intermediate' ? '+250 PTS' : '+500 PTS');
                return `
                <div class="tier-column">
                    <div class="tier-header">
                        <span class="tier-badge ${cls}"><i class="fa-solid fa-layer-group"></i> ${lvl} LEVEL</span>
                        <span class="tier-points">${pts} EACH</span>
                    </div>
                    ${chals[lvl].map(ch => {
                        const isFinished = finishedChallenges.includes(ch[2]);
                        return `
                        <div class="challenge-item ${isFinished ? 'solved' : ''}" id="ch-${ch[2]}" onclick="openModal('${ch[2]}', '${ch[0]}', '${ch[1]}')">
                            <div class="ch-item-head">
                                <span class="ch-node-id">NODE // ${ch[2].toUpperCase()}</span>
                                <span class="ch-status-pill">${isFinished ? '<i class="fa-solid fa-check"></i> SOLVED' : 'UNLOCKED'}</span>
                            </div>
                            <h4 class="ch-name">${ch[0]}</h4>
                            <div class="ch-item-foot">
                                <span class="ch-tag">${ch[3] || 'Exploit Node'}</span>
                                <i class="fa-solid ${isFinished ? 'fa-circle-check' : 'fa-arrow-right'} ch-action-icon"></i>
                            </div>
                        </div>`;
                    }).join('')}
                </div>`;
            }).join('')}
        </div>
    `;
}

function openModal(id, name, path) {
    currentChallengeId = id;
    document.getElementById('modalTitle').innerText = name + ' // TARGET ACQUIRED';
    document.getElementById('modalScenario').innerText = scenarios[id] || scenarios['default'];
    document.getElementById('btnAccessLab').href = path;
    document.getElementById('btnStart').style.display = 'inline-flex';
    document.getElementById('btnAccessLab').style.display = 'none';
    
    const input = document.getElementById('flagInput');
    const status = document.getElementById('flagStatus');
    const isSolved = finishedChallenges.includes(id);

    input.value = isSolved ? (flags[id] || '') : '';
    status.innerText = isSolved ? 'FLAG VALIDATED — MISSION COMPLETED' : 'FLAG VALIDATION CONSOLE';
    status.style.color = isSolved ? '#10b981' : '';

    document.getElementById('modalOverlay').style.display = 'flex';
    input.focus();
}

function startChallenge() {
    document.getElementById('btnStart').style.display = 'none';
    const accessBtn = document.getElementById('btnAccessLab');
    accessBtn.style.display = 'inline-flex';
    accessBtn.click();
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
        status.innerText = 'ACCESS GRANTED // FLAG ACCEPTED (+POINTS REWARDED)';
        status.style.color = '#10b981';

        const card = document.getElementById('ch-' + currentChallengeId);
        if (card) {
            card.classList.add('solved');
            const icon = card.querySelector('.ch-action-icon');
            if (icon) icon.className = 'fa-solid fa-circle-check ch-action-icon';
            const pill = card.querySelector('.ch-status-pill');
            if (pill) pill.innerHTML = '<i class="fa-solid fa-check"></i> SOLVED';
        }
        
        updateTelemetryStats();

        confetti({
            particleCount: 160,
            spread: 90,
            origin: { y: 0.6 },
            colors: ['#ff2a2f', '#10b981', '#ffffff', '#38bdf8']
        });
    } else {
        status.innerText = 'ACCESS DENIED // INCORRECT FLAG HASH';
        status.style.color = '#ff2a2f';
    }
}

function showHub() {
    document.getElementById('detailPage').style.display = 'none';
    document.getElementById('hubPage').style.display = 'block';
    updateTelemetryStats();
}

function closeModal(e) {
    if (e.target.id === 'modalOverlay') {
        document.getElementById('modalOverlay').style.display = 'none';
    }
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        if (document.getElementById('modalOverlay').style.display === 'flex') {
            document.getElementById('modalOverlay').style.display = 'none';
        } else if (document.getElementById('detailPage').style.display === 'block') {
            showHub();
        }
    }
});

function filterModules() {
    const term = document.getElementById('moduleSearch').value.toLowerCase().trim();
    const cards = document.querySelectorAll('.module-card');
    cards.forEach(card => {
        const title = card.getAttribute('data-title').toLowerCase();
        const tag = card.getAttribute('data-tag').toLowerCase();
        if (title.includes(term) || tag.includes(term)) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

function setCategoryFilter(filter, btn) {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const cards = document.querySelectorAll('.module-card');
    cards.forEach(card => {
        const tag = card.getAttribute('data-tag');
        if (filter === 'ALL') {
            card.style.display = 'flex';
        } else if (filter === 'ACCESS' && tag.includes('ACCESS')) {
            card.style.display = 'flex';
        } else if (filter === 'INJECTION' && tag.includes('INJECTION')) {
            card.style.display = 'flex';
        } else if (filter === 'CRYPTO' && tag.includes('CRYPTO')) {
            card.style.display = 'flex';
        } else if (filter === 'SUPPLY' && tag.includes('SUPPLY')) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

// Initial Telemetry Boot
updateTelemetryStats();
</script>
</body>
</html>
