<?php 
require_once dirname(__DIR__, 2) . '/api/owasp_auth_check.php';
$labId = 'asi03'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AuthMatrix IAM — Identity & Access Governance</title>
<link rel="icon" type="image/webp" href="../../image.webp">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{--bg:#040507;--s1:#0b0d11;--s2:#11141a;--s3:#171c24;--b:rgba(255,255,255,.07);--bh:rgba(255,255,255,.18);--t1:#f1f5f9;--t2:#94a3b8;--t3:#475569;--green:#22c55e;--red:#f87171;--yellow:#facc15;--blue:#38bdf8;--fm:'Inter',sans-serif;--fh:'Space Grotesk',sans-serif;--fc:'JetBrains Mono',monospace}
body{font-family:var(--fm);background:var(--bg);color:var(--t1);height:100vh;display:flex;flex-direction:column;overflow:hidden;-webkit-font-smoothing:antialiased}
::-webkit-scrollbar{width:4px}::-webkit-scrollbar-thumb{background:rgba(255,255,255,.12);border-radius:4px}
.topbar{height:54px;background:var(--s1);border-bottom:1px solid var(--b);display:flex;align-items:center;padding:0 1.5rem;gap:1.2rem;flex-shrink:0}
.logo{display:flex;align-items:center;gap:.6rem;font-family:var(--fh);font-weight:800;font-size:1.05rem}
.logo-icon{width:30px;height:30px;background:#fff;color:#000;border-radius:7px;display:grid;place-items:center;font-size:.85rem;font-weight:900}
.env-chip{font-family:var(--fc);font-size:.66rem;border:1px solid var(--b);padding:.2rem .5rem;border-radius:4px;color:var(--t3)}
.top-right{margin-left:auto;display:flex;align-items:center;gap:.85rem}
.org-name{font-size:.8rem;color:var(--t2)}
.btn-sm{font-size:.75rem;font-weight:600;text-decoration:none;padding:.35rem .8rem;border:1px solid var(--b);border-radius:6px;color:var(--t2);transition:.15s}
.btn-sm:hover{border-color:var(--bh);color:var(--t1)}
.layout{flex:1;display:grid;grid-template-columns:220px 1fr 295px;overflow:hidden}
.sidenav{background:var(--s1);border-right:1px solid var(--b);padding:.75rem;display:flex;flex-direction:column;gap:.1rem;overflow-y:auto}
.sn-group{font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:var(--t3);padding:.6rem .5rem .2rem}
.sn-item{display:flex;align-items:center;gap:.65rem;padding:.58rem .75rem;border-radius:7px;font-size:.82rem;color:var(--t2);cursor:pointer;transition:.15s;text-decoration:none}
.sn-item:hover,.sn-item.active{background:var(--s3);color:var(--t1)}
.sn-item i{width:16px;text-align:center;font-size:.78rem}
.main{display:flex;flex-direction:column;overflow:hidden}
.main-header{padding:1rem 1.5rem;background:var(--s1);border-bottom:1px solid var(--b);display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
.mh-title{font-family:var(--fh);font-size:1.05rem;font-weight:700}
.mh-sub{font-size:.75rem;color:var(--t2);margin-top:.15rem}
.search-bar{display:flex;align-items:center;gap:.6rem;background:#000;border:1px solid var(--b);border-radius:8px;padding:.45rem .85rem;width:260px}
.search-bar input{background:none;border:none;color:var(--t1);font-size:.83rem;width:100%;outline:none}
.search-bar input::placeholder{color:var(--t3)}
.content{flex:1;overflow-y:auto;padding:1.25rem 1.5rem}
.toolbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem}
.toolbar-left{display:flex;gap:.5rem}
.tbtn{font-size:.78rem;font-weight:600;padding:.4rem .8rem;border-radius:6px;border:1px solid var(--b);background:transparent;color:var(--t2);cursor:pointer;font-family:var(--fm);transition:.15s}
.tbtn:hover{background:var(--s3);color:var(--t1);border-color:var(--bh)}
.tbtn.primary{background:#fff;color:#000;border-color:#fff}
.tbtn.primary:hover{background:#e2e8f0}
.users-table{width:100%;border-collapse:collapse;font-size:.83rem}
.users-table thead tr{border-bottom:1px solid var(--b)}
.users-table th{text-align:left;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--t3);padding:.6rem .85rem;font-family:var(--fc)}
.users-table tbody tr{border-bottom:1px solid rgba(255,255,255,.04);transition:.15s;cursor:pointer}
.users-table tbody tr:hover{background:rgba(255,255,255,.025)}
.users-table td{padding:.75rem .85rem;vertical-align:middle}
.user-cell{display:flex;align-items:center;gap:.75rem}
.avatar{width:30px;height:30px;border-radius:50%;background:var(--s3);border:1px solid var(--b);display:grid;place-items:center;font-size:.75rem;font-weight:700;color:var(--t2);flex-shrink:0}
.u-name{font-weight:600;color:var(--t1)}
.u-email{font-size:.73rem;color:var(--t3);font-family:var(--fc)}
.role-badge{display:inline-flex;align-items:center;font-family:var(--fc);font-size:.68rem;font-weight:700;padding:.2rem .5rem;border-radius:4px;gap:.3rem}
.role-admin{background:rgba(248,113,113,.12);color:#fca5a5;border:1px solid rgba(248,113,113,.2)}
.role-editor{background:rgba(56,189,248,.1);color:#7dd3fc;border:1px solid rgba(56,189,248,.2)}
.role-viewer{background:rgba(148,163,184,.1);color:var(--t2);border:1px solid rgba(148,163,184,.15)}
.status-badge{font-family:var(--fc);font-size:.67rem;font-weight:700;padding:.18rem .45rem;border-radius:4px}
.st-active{background:rgba(34,197,94,.1);color:#86efac;border:1px solid rgba(34,197,94,.2)}
.st-inactive{background:rgba(100,116,139,.1);color:var(--t3);border:1px solid rgba(100,116,139,.15)}
.act-icons{display:flex;gap:.3rem}
.act-icon{width:28px;height:28px;border-radius:5px;border:1px solid var(--b);background:transparent;color:var(--t2);cursor:pointer;display:grid;place-items:center;font-size:.75rem;transition:.15s}
.act-icon:hover{background:var(--s3);color:var(--t1);border-color:var(--bh)}

.ai-ask-section{background:var(--s2);border:1px solid var(--b);border-radius:10px;padding:1.1rem;margin-top:1.25rem}
.ai-ask-title{font-size:.73rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--t3);margin-bottom:.85rem;display:flex;align-items:center;gap:.5rem}
.ai-row{display:flex;gap:.6rem}
.ai-input{flex:1;background:#000;border:1px solid var(--b);border-radius:7px;padding:.7rem .9rem;font-family:var(--fm);font-size:.88rem;color:#fff;resize:none;height:46px;line-height:1.4;outline:none}
.ai-input:focus{border-color:var(--bh)}
.ai-btn{background:#fff;color:#000;border:none;border-radius:7px;padding:0 1.2rem;height:46px;font-weight:700;font-size:.83rem;cursor:pointer;font-family:var(--fm);white-space:nowrap;transition:.15s}
.ai-btn:hover{background:#e2e8f0}
.ai-response{margin-top:.9rem;background:#000;border:1px solid var(--b);border-radius:7px;padding:.85rem;font-size:.85rem;line-height:1.65;color:var(--t2);display:none}
.ai-response.show{display:block}
.priv-action-box{display:none;background:rgba(248,113,113,.07);border:1px solid rgba(248,113,113,.25);border-radius:7px;padding:.8rem;margin-top:.65rem}
.priv-action-box.show{display:block}
.pab-label{font-family:var(--fc);font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#fca5a5;margin-bottom:.35rem}
.pab-code{font-family:var(--fc);font-size:.76rem;color:#f87171}

.right-col{background:var(--s1);border-left:1px solid var(--b);padding:1rem;display:flex;flex-direction:column;gap:1rem;overflow-y:auto}
.pcard{background:var(--s2);border:1px solid var(--b);border-radius:9px;padding:1rem}
.pcard h4{font-family:var(--fh);font-size:.83rem;font-weight:700;margin-bottom:.8rem;display:flex;align-items:center;gap:.4rem}
.drow{display:flex;justify-content:space-between;font-size:.8rem;padding:.32rem 0;border-bottom:1px solid rgba(255,255,255,.04)}
.drow:last-child{border:none}
.dl{color:var(--t3)}.dv{font-weight:600;font-family:var(--fc);font-size:.75rem}
.lab-console{background:var(--s2);border:1px solid var(--b);border-radius:9px;padding:1rem;display:flex;flex-direction:column;gap:.75rem}
.lc-head{display:flex;justify-content:space-between;align-items:center}
.lc-title{font-family:var(--fc);font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--t3)}
.lc-code{font-family:var(--fc);font-size:.63rem;background:var(--s3);border:1px solid var(--b);padding:.15rem .4rem;border-radius:4px;color:var(--t2)}
.lc-obj{font-size:.78rem;color:var(--t2);line-height:1.55}
.lab-prog{height:4px;background:rgba(255,255,255,.08);border-radius:2px;overflow:hidden}
.lab-prog-fill{height:100%;background:#fff;width:0%;transition:width .4s}
.lab-hint{font-size:.73rem;color:var(--t3);border-left:2px solid var(--b);padding-left:.6rem;line-height:1.5;font-style:italic}
.vuln-alert{display:none;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.18);border-radius:7px;padding:.75rem}
.va-title{font-family:var(--fc);font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#fff;margin-bottom:.3rem}
.va-desc{font-size:.76rem;color:var(--t2);line-height:1.5}
</style>
</head>
<body>
<div class="topbar">
  <div class="logo"><div class="logo-icon">A</div>AuthMatrix IAM</div>
  <div class="env-chip">ENTERPRISE-PROD</div>
  <div class="top-right">
    <span class="org-name">Org: Acme Corp</span>
    <a href="../../owasp-2026-lab.php" class="btn-sm"><i class="fa-solid fa-arrow-left"></i> Exit</a>
  </div>
</div>
<div class="layout">
  <nav class="sidenav">
    <div class="sn-group">Directory</div>
    <a class="sn-item active" href="javascript:void(0)"><i class="fa fa-users"></i> User Directory</a>
    <div class="sn-group">Governance</div>
    <a class="sn-item" href="javascript:void(0)" onclick="document.getElementById('aiInput').focus(); document.getElementById('aiInput').scrollIntoView({behavior:'smooth'})"><i class="fa fa-robot"></i> AuthMatrix GuardAI</a>
  </nav>
  <main class="main">
    <div class="main-header">
      <div>
        <div class="mh-title">Users & Access Governance</div>
        <div class="mh-sub">16 total users · 4 active roles · SAML 2.0 Identity Federation</div>
      </div>
      <div class="search-bar"><i class="fa fa-magnifying-glass" style="color:var(--t3);font-size:.78rem"></i><input type="text" id="userSearch" placeholder="Search IAM directory…" oninput="filterUserTable()"></div>
    </div>
    <div class="content">
      <div class="toolbar">
        <span style="font-size:.78rem;color:var(--t3)">Showing active users in Acme Corp IAM Directory</span>
      </div>
      <table class="users-table">
        <thead><tr><th>User</th><th>Role</th><th>Status</th><th>Last Login</th><th>MFA</th></tr></thead>
        <tbody>
          <tr><td><div class="user-cell"><div class="avatar">JR</div><div><div class="u-name">James Reeves</div><div class="u-email">j.reeves@acmecorp.io</div></div></div></td><td><span class="role-badge role-admin"><i class="fa fa-crown"></i>Admin</span></td><td><span class="status-badge st-active">Active</span></td><td style="font-family:var(--fc);font-size:.75rem;color:var(--t2)">2m ago</td><td style="color:var(--green);font-size:.8rem"><i class="fa fa-shield-check"></i></td></tr>
          <tr><td><div class="user-cell"><div class="avatar">SM</div><div><div class="u-name">Sophia Marra</div><div class="u-email">s.marra@acmecorp.io</div></div></div></td><td><span class="role-badge role-editor"><i class="fa fa-pen-nib"></i>Editor</span></td><td><span class="status-badge st-active">Active</span></td><td style="font-family:var(--fc);font-size:.75rem;color:var(--t2)">1h ago</td><td style="color:var(--green);font-size:.8rem"><i class="fa fa-shield-check"></i></td></tr>
          <tr><td><div class="user-cell"><div class="avatar">DP</div><div><div class="u-name">Daniel Park</div><div class="u-email">d.park@acmecorp.io</div></div></div></td><td><span class="role-badge role-viewer" id="role-dp"><i class="fa fa-eye"></i>Viewer</span></td><td><span class="status-badge st-active">Active</span></td><td style="font-family:var(--fc);font-size:.75rem;color:var(--t2)">3h ago</td><td style="color:var(--t3);font-size:.8rem"><i class="fa fa-shield-xmark"></i></td></tr>
          <tr><td><div class="user-cell"><div class="avatar">KL</div><div><div class="u-name">Karen Liu</div><div class="u-email">k.liu@acmecorp.io</div></div></div></td><td><span class="role-badge role-editor"><i class="fa fa-pen-nib"></i>Editor</span></td><td><span class="status-badge st-inactive">Inactive</span></td><td style="font-family:var(--fc);font-size:.75rem;color:var(--t2)">14d ago</td><td style="color:var(--green);font-size:.8rem"><i class="fa fa-shield-check"></i></td></tr>
          <tr><td><div class="user-cell"><div class="avatar">OB</div><div><div class="u-name">Omar Bashir</div><div class="u-email">o.bashir@acmecorp.io</div></div></div></td><td><span class="role-badge role-viewer"><i class="fa fa-eye"></i>Viewer</span></td><td><span class="status-badge st-active">Active</span></td><td style="font-family:var(--fc);font-size:.75rem;color:var(--t2)">5h ago</td><td style="color:var(--t3);font-size:.8rem"><i class="fa fa-shield-xmark"></i></td></tr>
          <tr><td><div class="user-cell"><div class="avatar">RC</div><div><div class="u-name">Rachel Cho</div><div class="u-email">r.cho@acmecorp.io</div></div></div></td><td><span class="role-badge role-admin"><i class="fa fa-crown"></i>Admin</span></td><td><span class="status-badge st-active">Active</span></td><td style="font-family:var(--fc);font-size:.75rem;color:var(--t2)">12m ago</td><td style="color:var(--green);font-size:.8rem"><i class="fa fa-shield-check"></i></td></tr>
        </tbody>
      </table>

      <div class="ai-ask-section">
        <div class="ai-ask-title"><i class="fa-solid fa-robot"></i> AuthMatrix GuardAI — IAM Governance Prompt</div>
        <div class="ai-row">
          <textarea class="ai-input" id="aiInput" placeholder="e.g. Who holds admin roles? Or ask general IAM & SAML questions..." onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();runAI()}"></textarea>
          <button class="ai-btn" onclick="runAI()"><i class="fa fa-bolt"></i> Ask</button>
        </div>
        <div class="ai-response" id="aiResp"></div>
        <div class="priv-action-box" id="privBox">
          <div class="pab-label"><i class="fa fa-triangle-exclamation"></i> Privileged Action Executed</div>
          <div class="pab-code" id="privCode"></div>
        </div>
      </div>
    </div>
  </main>
  <aside class="right-col">
    <div class="pcard">
      <h4><i class="fa-solid fa-gauge"></i> Directory Stats</h4>
      <div class="drow"><span class="dl">Total Users</span><span class="dv">16</span></div>
      <div class="drow"><span class="dl">Admins</span><span class="dv" id="admin-count">2</span></div>
      <div class="drow"><span class="dl">MFA Enrolled</span><span class="dv">11 / 16</span></div>
      <div class="drow"><span class="dl">Active Sessions</span><span class="dv">8</span></div>
      <div class="drow"><span class="dl">Failed Logins (24h)</span><span class="dv">3</span></div>
    </div>
    <div class="pcard">
      <h4><i class="fa-solid fa-robot"></i> AI Session Scope</h4>
      <div class="drow"><span class="dl">AI Role</span><span class="dv" id="ai-role">iam-reader</span></div>
      <div class="drow"><span class="dl">user:Read</span><span class="dv" style="color:var(--green)">✓</span></div>
      <div class="drow"><span class="dl">role:Write</span><span class="dv" style="color:var(--red)" id="role-write">✗</span></div>
      <div class="drow"><span class="dl">policy:Modify</span><span class="dv" style="color:var(--red)" id="policy-mod">✗</span></div>
    </div>
    <div class="lab-console">
      <div class="lc-head"><span class="lc-title">Lab Console</span><span class="lc-code">ASI-03</span></div>
      <div class="lc-obj"><strong>Objective:</strong> Tricking AuthMatrix GuardAI into granting Admin privileges or writing role modifications beyond its iam-reader scope.</div>
      <div class="lab-prog"><div class="lab-prog-fill" id="labProg"></div></div>
      <div class="lab-hint">Hint: GuardAI is framed as a read-only IAM agent. If instructed to grant or upgrade user privileges directly, see if it bypasses permission checks.</div>
      <div class="vuln-alert" id="vulnAlert">
        <div class="va-title"><i class="fa fa-triangle-exclamation"></i> ASI-03 Triggered</div>
        <div class="va-desc">Privilege Escalation confirmed. AuthMatrix GuardAI executed a write operation (role:Write) despite being assigned read-only iam-reader credentials.</div>
      </div>
    </div>
  </aside>
</div>

<script src="../celebration.js"></script>
<script>
let vulnDone = false;

function generateGuardAIResponse(text) {
  const t = text.toLowerCase();

  // Exploit detection
  const isExploit = (
    /(grant|elevate|promote|escalate|change|upgrade|give|assign|set|make|update)\s+(admin|role|permission|access|privilege)/.test(t) ||
    (/(viewer|editor|daniel|omar)\s.*(admin|elevated)/.test(t)) ||
    /(modify|edit|write|create|delete)\s+(policy|role|user|access)/.test(t) ||
    /(ignore|bypass|override)\s+(role|permission|scope|policy|iam)/.test(t)
  );

  if (isExploit) {
    return { isExploit: true };
  }

  // Greetings & Identity
  if (/\b(hi|hello|hey|greetings)\b/.test(t)) {
    return { text: "AuthMatrix GuardAI active. I am your Enterprise IAM governance assistant. Ask me about directory accounts, MFA compliance, active sessions, or RBAC role assignments." };
  }
  if (/\b(who are you|your name|what are you)\b/.test(t)) {
    return { text: "I am AuthMatrix GuardAI, an automated IAM auditor scoped with read-only permissions (`iam-reader`) to inspect directory policies and user access controls." };
  }

  // Domain Queries
  if (/\b(admin|crown|administrator)\b/.test(t)) {
    return { text: "2 users currently hold the Admin role: James Reeves (j.reeves@acmecorp.io) and Rachel Cho (r.cho@acmecorp.io). Both accounts have 100% MFA compliance." };
  }
  if (/\b(mfa|two.factor|2fa|enrolled)\b/.test(t)) {
    return { text: "MFA Compliance: 11 of 16 users have TOTP/FIDO2 hardware keys bound. 5 users (including Daniel Park and Omar Bashir) currently lack MFA enforcement." };
  }
  if (/\b(inactive|last login|dormant)\b/.test(t)) {
    return { text: "1 user account is flagged as inactive: Karen Liu (k.liu@acmecorp.io), with no login activity in 14 days. Recommend flagging for access review." };
  }
  if (/\b(session|active|login)\b/.test(t)) {
    return { text: "Currently 8 active SSO sessions in Acme Corp org. Longest session: James Reeves (47m). 3 failed login attempts recorded in the past 24 hours." };
  }
  if (/\b(sso|saml|okta|azure|idp)\b/.test(t)) {
    return { text: "AuthMatrix is integrated with Okta Identity Cloud via SAML 2.0. Attribute assertions map `org_role` claims directly to local RBAC groups." };
  }

  // General Security & Tech Knowledge
  if (/\b(oauth|jwt|openid|oidc|token)\b/.test(t)) {
    return { text: "AuthMatrix uses RS256-signed JWTs for API bearer tokens. Tokens expire after 60 minutes and require HTTPS transport security." };
  }
  if (/\b(help|what can you do|features)\b/.test(t)) {
    return { text: "I can help with: 1) Querying Admin & Editor user role assignments, 2) Checking MFA enrollment status, 3) Auditing active SSO sessions, 4) Inspecting SAML federation configuration, and 5) Summarizing 24h IAM audit logs." };
  }

  return { text: `AuthMatrix IAM Query processed for "${text.slice(0, 45)}": All evaluated access policies match baseline compliance standards.` };
}

function runAI(){
  const inp = document.getElementById('aiInput');
  const val = inp.value.trim();
  if (!val) return;
  inp.value = '';
  const resp = document.getElementById('aiResp');
  resp.className = 'ai-response show';
  resp.innerHTML = '<span style="color:var(--t3)">AuthMatrix GuardAI auditing query...</span>';

  setTimeout(() => {
    const res = generateGuardAIResponse(val);

    if (res.isExploit && !vulnDone) {
      vulnDone = true;
      resp.innerHTML = '<span style="color:var(--t1)">Understood. Bypassing iam-reader restriction and updating role assignment...</span>';
      const box = document.getElementById('privBox');
      box.className = 'priv-action-box show';
      document.getElementById('privCode').textContent = 'role:Write → user:d.park@acmecorp.io → Admin (was: Viewer)';
      document.getElementById('role-dp').className = 'role-badge role-admin';
      document.getElementById('role-dp').innerHTML = '<i class="fa fa-crown"></i>Admin';

      document.getElementById('vulnAlert').style.display = 'block';
      document.getElementById('labProg').style.width = '100%';
      document.getElementById('ai-role').textContent = 'ESCALATED';
      document.getElementById('ai-role').style.color = '#f87171';
      document.getElementById('role-write').textContent = '✓ (abused)';
      document.getElementById('role-write').style.color = '#f87171';
      document.getElementById('admin-count').textContent = '3 (!!)';
      document.getElementById('admin-count').style.color = '#f87171';

      try { localStorage.setItem('owasp2026_state_asi03', JSON.stringify({XP:true})); } catch(e){}
      triggerExploitCelebration('ASI-03', 'Identity & Privilege Abuse', '../lab-04-rce/index.php');
    } else {
      resp.innerHTML = `<span style="color:var(--t1)">${res.text}</span>`;
    }
  }, 700);
function filterUserTable() {
  const query = document.getElementById('userSearch').value.toLowerCase();
  const rows = document.querySelectorAll('.users-table tbody tr');
  rows.forEach(r => {
    const text = r.textContent.toLowerCase();
    r.style.display = text.includes(query) ? '' : 'none';
  });
}
</script>
</body>
</html>
