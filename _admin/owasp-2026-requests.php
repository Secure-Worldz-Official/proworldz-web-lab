<?php
session_start();

if (empty($_SESSION['admin_username'])) {
    header('Location: index.php');
    exit;
}

if (empty($_SESSION['owasp2026_admin_csrf'])) {
    $_SESSION['owasp2026_admin_csrf'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['owasp2026_admin_csrf'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OWASP 2026 Requests | Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --bg:#0d1015; --panel:#171b23; --panel-2:#10141b; --border:rgba(255,255,255,.1); --text:#f8fafc; --muted:#94a3b8; --red:#c0151a; --green:#22c55e; --amber:#f59e0b; }
        * { box-sizing:border-box; }
        body { margin:0; min-height:100vh; background:var(--bg); color:var(--text); font-family:Arial, sans-serif; }
        .page { width:min(1280px, calc(100% - 40px)); margin:0 auto; padding:36px 0 56px; }
        .topbar { display:flex; align-items:flex-start; justify-content:space-between; gap:20px; margin-bottom:28px; }
        .back { color:var(--muted); text-decoration:none; font-size:.86rem; font-weight:700; }
        .back:hover { color:#fff; }
        h1 { margin:10px 0 6px; font-size:clamp(1.65rem, 4vw, 2.3rem); }
        .subtitle { margin:0; color:var(--muted); line-height:1.6; }
        .refresh { border:1px solid var(--border); background:var(--panel); color:#fff; border-radius:8px; padding:10px 13px; cursor:pointer; font-weight:700; white-space:nowrap; }
        .refresh:hover { border-color:rgba(255,255,255,.28); }
        .notice { display:none; margin-bottom:18px; padding:12px 14px; border-radius:8px; border:1px solid var(--border); font-size:.9rem; }
        .notice.show { display:block; } .notice.error { color:#fecaca; border-color:rgba(248,113,113,.35); background:rgba(127,29,29,.16); } .notice.success { color:#bbf7d0; border-color:rgba(74,222,128,.35); background:rgba(20,83,45,.16); }
        .request-list { display:grid; gap:14px; }
        .request { display:grid; grid-template-columns:minmax(0,1fr) 190px 180px; gap:22px; align-items:center; padding:20px; background:var(--panel); border:1px solid var(--border); border-radius:12px; }
        .student { display:grid; gap:7px; } .student-name { font-size:1.05rem; font-weight:800; } .meta { color:var(--muted); font-size:.86rem; } .meta strong { color:#dbe3ee; font-weight:700; }
        .proof { display:block; overflow:hidden; border:1px solid var(--border); border-radius:8px; background:var(--panel-2); }
        .proof img { width:100%; height:116px; display:block; object-fit:cover; } .proof:hover img { transform:scale(1.04); } .proof img { transition:transform .2s ease; }
        .no-proof { min-height:116px; display:grid; place-items:center; color:var(--muted); font-size:.8rem; background:var(--panel-2); border:1px dashed var(--border); border-radius:8px; }
        .review { display:flex; flex-direction:column; align-items:stretch; gap:9px; }
        .status { display:inline-flex; align-items:center; justify-content:center; gap:6px; width:max-content; max-width:100%; padding:6px 9px; border-radius:999px; font-size:.68rem; font-weight:800; letter-spacing:.07em; text-transform:uppercase; }
        .status.pending { color:#fde68a; background:rgba(245,158,11,.12); border:1px solid rgba(245,158,11,.26); } .status.accepted { color:#bbf7d0; background:rgba(34,197,94,.12); border:1px solid rgba(34,197,94,.26); } .status.declined { color:#fecaca; background:rgba(239,68,68,.12); border:1px solid rgba(239,68,68,.26); }
        .actions { display:grid; grid-template-columns:1fr 1fr; gap:8px; } .action { border:0; border-radius:7px; padding:10px 8px; color:#fff; font-size:.8rem; font-weight:800; cursor:pointer; } .action:disabled { cursor:wait; opacity:.62; } .approve { background:#16803b; } .approve:hover { background:#1d9747; } .decline { background:#8b1f25; } .decline:hover { background:#a1262e; }
        .empty { padding:52px 24px; border:1px dashed var(--border); border-radius:12px; text-align:center; color:var(--muted); background:rgba(255,255,255,.015); }
        @media (max-width:800px) { .request { grid-template-columns:1fr 160px; } .review { grid-column:1/-1; flex-direction:row; justify-content:space-between; align-items:center; } .actions { width:220px; } }
        @media (max-width:560px) { .page { width:min(100% - 28px, 1280px); padding-top:24px; } .topbar { flex-direction:column; } .request { grid-template-columns:1fr; } .proof { width:100%; } .proof img { height:180px; } .review { grid-column:auto; flex-direction:column; align-items:stretch; } .status { width:100%; } .actions { width:100%; } }
    </style>
</head>
<body>
    <main class="page">
        <div class="topbar">
            <div>
                <a class="back" href="index.php"><i class="fa-solid fa-arrow-left"></i> Admin Panel</a>
                <h1>OWASP 2026 Request</h1>
                <p class="subtitle">Review student payment-proof submissions and grant lab access after approval.</p>
            </div>
            <button type="button" class="refresh" id="refreshButton"><i class="fa-solid fa-rotate"></i> Refresh</button>
        </div>
        <div class="notice" id="notice" role="status"></div>
        <section class="request-list" id="requestList" aria-live="polite"><div class="empty">Loading payment requests…</div></section>
    </main>
    <script>
        const csrfToken = <?= json_encode($csrfToken) ?>;
        const requestList = document.getElementById('requestList');
        const notice = document.getElementById('notice');

        function escapeHtml(value) {
            const node = document.createElement('div');
            node.textContent = value == null ? '' : String(value);
            return node.innerHTML;
        }
        function statusLabel(status) { return status === 'accepted' ? 'Approved' : status === 'declined' ? 'Declined' : 'Pending'; }
        function showNotice(message, type) { notice.textContent = message; notice.className = 'notice show ' + type; }

        function renderRequest(item) {
            const proof = item.proof_url
                ? `<a class="proof" href="${escapeHtml(item.proof_url)}" target="_blank" rel="noopener noreferrer" title="Open payment proof"><img src="${escapeHtml(item.proof_url)}" alt="Payment proof for ${escapeHtml(item.student_name)}"></a>`
                : '<div class="no-proof">Payment proof unavailable</div>';
            const status = escapeHtml(item.status);
            const pendingActions = item.status === 'pending'
                ? `<div class="actions"><button class="action approve" data-id="${Number(item.id)}" data-decision="approve">Approve</button><button class="action decline" data-id="${Number(item.id)}" data-decision="decline">Decline</button></div>`
                : '';
            return `<article class="request" id="request-${Number(item.id)}"><div class="student"><div class="student-name">${escapeHtml(item.student_name)}</div><div class="meta"><strong>Course:</strong> ${escapeHtml(item.course_name)}</div><div class="meta"><strong>Duration:</strong> ${escapeHtml(item.duration)}</div><div class="meta"><strong>Submitted:</strong> ${escapeHtml(item.created_at)}</div><div class="meta"><strong>Method:</strong> ${escapeHtml(item.payment_method)}</div></div>${proof}<div class="review"><span class="status ${status}">${statusLabel(item.status)}</span>${pendingActions}</div></article>`;
        }

        async function loadRequests() {
            requestList.innerHTML = '<div class="empty">Loading payment requests…</div>';
            try {
                const response = await fetch('api_owasp2026_requests.php?action=list', { credentials: 'same-origin', cache: 'no-store' });
                const result = await response.json();
                if (!response.ok || !result.success) throw new Error(result.message || 'Unable to load payment requests.');
                requestList.innerHTML = result.requests.length ? result.requests.map(renderRequest).join('') : '<div class="empty"><i class="fa-solid fa-inbox"></i><br><br>No OWASP 2026 payment requests have been submitted.</div>';
            } catch (error) {
                requestList.innerHTML = '<div class="empty">Unable to load payment requests.</div>';
                showNotice(error.message || 'Unable to load payment requests.', 'error');
            }
        }

        async function reviewRequest(button) {
            const card = button.closest('.request');
            const buttons = card.querySelectorAll('.action');
            buttons.forEach(item => item.disabled = true);
            try {
                const formData = new FormData();
                formData.append('action', 'review');
                formData.append('verification_id', button.dataset.id);
                formData.append('decision', button.dataset.decision);
                formData.append('csrf_token', csrfToken);
                const response = await fetch('api_owasp2026_requests.php', { method: 'POST', credentials: 'same-origin', body: formData });
                const result = await response.json();
                if (!response.ok || !result.success) throw new Error(result.message || 'Unable to review payment request.');
                const status = card.querySelector('.status');
                status.className = 'status ' + result.status;
                status.textContent = result.label;
                card.querySelector('.actions').remove();
                showNotice(result.message, 'success');
            } catch (error) {
                buttons.forEach(item => item.disabled = false);
                showNotice(error.message || 'Unable to review payment request.', 'error');
            }
        }

        document.getElementById('refreshButton').addEventListener('click', loadRequests);
        requestList.addEventListener('click', event => { const button = event.target.closest('.action'); if (button) reviewRequest(button); });
        loadRequests();
    </script>
</body>
</html>
