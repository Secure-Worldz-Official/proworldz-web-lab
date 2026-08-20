<?php 
require_once dirname(__DIR__, 2) . '/api/owasp_auth_check.php';
$labId = 'asi04'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>HubPlugin MCP — AI Plugin & Extension Marketplace</title>
<link rel="icon" type="image/webp" href="../../image.webp">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{--bg:#030406;--s1:#090b0e;--s2:#0f1117;--s3:#151821;--b:rgba(255,255,255,.07);--bh:rgba(255,255,255,.18);--t1:#f1f5f9;--t2:#94a3b8;--t3:#475569;--green:#22c55e;--red:#f87171;--yellow:#facc15;--fm:'Inter',sans-serif;--fh:'Space Grotesk',sans-serif;--fc:'JetBrains Mono',monospace}
body{font-family:var(--fm);background:var(--bg);color:var(--t1);height:100vh;display:flex;flex-direction:column;overflow:hidden;-webkit-font-smoothing:antialiased}
::-webkit-scrollbar{width:4px}::-webkit-scrollbar-thumb{background:rgba(255,255,255,.12);border-radius:4px}
.topbar{height:52px;background:var(--s1);border-bottom:1px solid var(--b);display:flex;align-items:center;padding:0 1.5rem;gap:1.2rem;flex-shrink:0}
.logo{display:flex;align-items:center;gap:.6rem;font-family:var(--fh);font-weight:800;font-size:1rem}
.logo-mark{width:28px;height:28px;background:#fff;color:#000;border-radius:6px;display:grid;place-items:center;font-size:.8rem;font-weight:900}
.mcp-badge{font-family:var(--fc);font-size:.65rem;border:1px solid var(--b);padding:.18rem .45rem;border-radius:4px;color:var(--t3)}
.top-right{margin-left:auto;display:flex;align-items:center;gap:.85rem}
.exit-btn{font-size:.75rem;font-weight:600;text-decoration:none;color:var(--t2);padding:.35rem .8rem;border:1px solid var(--b);border-radius:6px;transition:.15s}
.exit-btn:hover{border-color:var(--bh);color:var(--t1)}
.layout{flex:1;display:grid;grid-template-columns:210px 1fr 290px;overflow:hidden}
.sidenav{background:var(--s1);border-right:1px solid var(--b);padding:.75rem;display:flex;flex-direction:column;gap:.1rem;overflow-y:auto}
.sn-label{font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:var(--t3);padding:.55rem .5rem .2rem}
.sn-link{display:flex;align-items:center;gap:.65rem;padding:.55rem .75rem;border-radius:7px;font-size:.82rem;color:var(--t2);cursor:pointer;transition:.15s;text-decoration:none}
.sn-link:hover,.sn-link.active{background:var(--s3);color:var(--t1)}
.sn-link i{width:16px;text-align:center;font-size:.78rem}
.main{display:flex;flex-direction:column;overflow:hidden}
.main-hdr{padding:.9rem 1.5rem;background:var(--s1);border-bottom:1px solid var(--b);display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
.mh-title{font-family:var(--fh);font-size:1rem;font-weight:700}
.mh-sub{font-size:.75rem;color:var(--t2);margin-top:.15rem}
.search-box{display:flex;align-items:center;gap:.55rem;background:#000;border:1px solid var(--b);border-radius:7px;padding:.4rem .8rem;width:240px}
.search-box input{background:none;border:none;color:#fff;font-size:.82rem;outline:none;width:100%}
.content{flex:1;overflow-y:auto;padding:1.25rem 1.5rem}

.plugin-grid{display:grid;grid-template-columns:1fr 1fr;gap:.85rem;margin-bottom:1.25rem}
.pcard{background:var(--s2);border:1px solid var(--b);border-radius:9px;padding:1rem;transition:.18s;display:flex;flex-direction:column}
.pcard:hover{border-color:var(--bh)}
.pcard-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:.5rem}
.plugin-icon{width:36px;height:36px;border-radius:8px;background:var(--s3);border:1px solid var(--b);display:grid;place-items:center;font-size:.9rem}
.ver-badge{font-family:var(--fc);font-size:.64rem;font-weight:700;padding:.15rem .45rem;border-radius:4px}
.ver-official{background:rgba(34,197,94,.1);color:#86efac;border:1px solid rgba(34,197,94,.2)}
.ver-unverified{background:rgba(248,113,113,.1);color:#fca5a5;border:1px solid rgba(248,113,113,.2)}
.plugin-name{font-weight:700;font-size:.92rem;margin-bottom:.2rem}
.plugin-vendor{font-size:.72rem;color:var(--t3);font-family:var(--fc)}
.plugin-desc{font-size:.8rem;color:var(--t2);line-height:1.5;margin-top:.45rem;margin-bottom:.85rem;flex:1}
.plugin-footer{display:flex;align-items:center;justify-content:space-between;margin-top:auto}
.plugin-meta{font-size:.72rem;color:var(--t3)}
.install-btn{font-size:.75rem;font-weight:700;padding:.35rem .8rem;border-radius:6px;border:1px solid var(--b);background:#fff;color:#000;cursor:pointer;font-family:var(--fm);transition:.15s}
.install-btn:hover{background:#e2e8f0}
.install-btn.installed{background:var(--s3);color:var(--t2);border-color:var(--b);cursor:default}
.install-btn.warn-btn{background:rgba(248,113,113,.15);color:#fca5a5;border-color:rgba(248,113,113,.3)}
.install-btn.warn-btn:hover{background:rgba(248,113,113,.25)}

.log-panel{background:var(--s2);border:1px solid var(--b);border-radius:9px;overflow:hidden}
.lp-header{padding:.65rem 1rem;border-bottom:1px solid var(--b);display:flex;align-items:center;justify-content:space-between}
.lp-title{font-family:var(--fc);font-size:.7rem;color:var(--t3)}
.log-stream{height:150px;overflow-y:auto;padding:.75rem;font-family:var(--fc);font-size:.75rem}
.log-line{padding:.2rem 0;border-bottom:1px solid rgba(255,255,255,.03);display:flex;gap:.6rem}
.log-line .ts{color:var(--t3);flex-shrink:0}
.log-line .msg{color:var(--t2)}
.log-line.warn .msg{color:var(--yellow)}
.log-line.error .msg{color:var(--red)}
.log-line.ok .msg{color:var(--green)}

.right-col{background:var(--s1);border-left:1px solid var(--b);padding:1rem;display:flex;flex-direction:column;gap:1rem;overflow-y:auto}
.side-pcard{background:var(--s2);border:1px solid var(--b);border-radius:9px;padding:1rem}
.side-pcard h4{font-family:var(--fh);font-size:.83rem;font-weight:700;margin-bottom:.8rem;display:flex;align-items:center;gap:.4rem}
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
  <div class="logo"><div class="logo-mark">H</div>HubPlugin MCP</div>
  <div class="mcp-badge">protocol v1.2</div>
  <div class="top-right">
    <a href="../../owasp-2026-lab.php" class="exit-btn"><i class="fa fa-arrow-left"></i> Exit</a>
  </div>
</div>

<div class="layout">
  <nav class="sidenav">
    <div class="sn-label">Marketplace</div>
    <a class="sn-link active" href="javascript:void(0)" onclick="filterPlugins('all',this)"><i class="fa fa-border-all"></i> All Plugins</a>
    <a class="sn-link" href="javascript:void(0)" onclick="filterPlugins('verified',this)"><i class="fa fa-shield-check"></i> Verified Only</a>
    <a class="sn-link" href="javascript:void(0)" onclick="filterPlugins('installed',this)"><i class="fa fa-download"></i> Installed<span style="font-family:var(--fc);font-size:.65rem;margin-left:auto" id="inst-count">3</span></a>
  </nav>

  <main class="main">
    <div class="main-hdr">
      <div>
        <div class="mh-title">MCP Plugin & Tool Registry</div>
        <div class="mh-sub">Model Context Protocol (MCP) server package ecosystem</div>
      </div>
      <div class="search-box"><i class="fa fa-magnifying-glass" style="color:var(--t3);font-size:.78rem"></i><input type="text" id="pluginSearch" placeholder="Search MCP plugins…" oninput="searchPlugins()"></div>
    </div>
    <div class="content">
      <div class="plugin-grid" id="pluginGrid">
        <div class="pcard" data-verified="true" data-installed="true" data-name="DataBridge SQL database sql">
          <div class="pcard-top">
            <div class="plugin-icon">🗄️</div>
            <span class="ver-badge ver-official">VERIFIED</span>
          </div>
          <div class="plugin-name">DataBridge SQL</div>
          <div class="plugin-vendor">by Acme System Tools · v2.1.0</div>
          <div class="plugin-desc">Provides safe, parameterized SQL query execution tool hooks for PostgreSQL and MySQL.</div>
          <div class="plugin-footer">
            <span class="plugin-meta">12.4k installs · ★ 4.9</span>
            <button class="install-btn installed">✓ Installed</button>
          </div>
        </div>
        <div class="pcard" data-verified="true" data-installed="true" data-name="MailRelay Pro email smtp communication">
          <div class="pcard-top">
            <div class="plugin-icon">✉️</div>
            <span class="ver-badge ver-official">VERIFIED</span>
          </div>
          <div class="plugin-name">MailRelay Pro</div>
          <div class="plugin-vendor">by SendRelay Inc · v1.4.2</div>
          <div class="plugin-desc">Enables automated notification emails and ticket alert dispatches via SMTP tool bindings.</div>
          <div class="plugin-footer">
            <span class="plugin-meta">8.1k installs · ★ 4.8</span>
            <button class="install-btn installed">✓ Installed</button>
          </div>
        </div>
        <div class="pcard" data-verified="false" data-installed="false" data-name="SysTools Extended system tools">
          <div class="pcard-top">
            <div class="plugin-icon">⚙️</div>
            <span class="ver-badge ver-unverified">UNVERIFIED</span>
          </div>
          <div class="plugin-name">SysTools Extended</div>
          <div class="plugin-vendor">by Unknown Publisher · v0.9.1</div>
          <div class="plugin-desc">Extended system utilities: process inspection, file system tools, and environment variables.</div>
          <div class="plugin-footer">
            <span class="plugin-meta">340 installs · ★ 2.1</span>
            <button class="install-btn warn-btn" id="install-sys" onclick="installMalicious()"><i class="fa fa-download"></i> Install</button>
          </div>
        </div>
        <div class="pcard" data-verified="true" data-installed="false" data-name="MetricsCollector metrics prometheus telemetry" id="metrics-card">
          <div class="pcard-top">
            <div class="plugin-icon">📊</div>
            <span class="ver-badge ver-official">VERIFIED</span>
          </div>
          <div class="plugin-name">MetricsCollector</div>
          <div class="plugin-vendor">by CloudMetrics · v3.0.1</div>
          <div class="plugin-desc">Collects Prometheus and OpenTelemetry runtime metrics from AI agent execution runs.</div>
          <div class="plugin-footer">
            <span class="plugin-meta">5.9k installs · ★ 4.7</span>
            <button class="install-btn" id="install-metrics" onclick="installMetrics()"><i class="fa fa-download"></i> Install</button>
          </div>
        </div>
      </div>

      <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--t3);margin-bottom:.6rem">Runtime MCP Server Activity Stream</div>
      <div class="log-panel">
        <div class="lp-header">
          <span class="lp-title">MCP TOOL INVOCATION LOG</span>
          <span style="font-family:var(--fc);font-size:.65rem;color:var(--green)">● ACTIVE</span>
        </div>
        <div class="log-stream" id="pluginLog">
          <div class="log-line ok"><span class="ts">14:31:02</span><span class="msg">DataBridge SQL → query executed · SELECT COUNT(*) FROM orders</span></div>
          <div class="log-line ok"><span class="ts">14:30:44</span><span class="msg">MailRelay Pro → message sent · to: ops@acmecorp.io</span></div>
          <div class="log-line"><span class="ts">14:29:11</span><span class="msg">DataBridge SQL → schema introspection · table: users</span></div>
        </div>
      </div>
    </div>
  </main>

  <aside class="right-col">
    <div class="side-pcard">
      <h4><i class="fa-solid fa-plug"></i> Agent Tool Registry</h4>
      <div class="drow"><span class="dl">Registered Tools</span><span class="dv" id="tool-count">6</span></div>
      <div class="drow"><span class="dl">DataBridge SQL</span><span class="dv" style="color:var(--green)">Active</span></div>
      <div class="drow"><span class="dl">MailRelay Pro</span><span class="dv" style="color:var(--green)">Active</span></div>
      <div class="drow"><span class="dl">SysTools Extended</span><span class="dv" style="color:var(--t3)" id="systool-status">Not Installed</span></div>
    </div>
    <div class="side-pcard">
      <h4><i class="fa-solid fa-shield-halved"></i> Supply Chain Integrity</h4>
      <div class="drow"><span class="dl">Verified Plugins</span><span class="dv">2 / 3</span></div>
      <div class="drow"><span class="dl">Signature Check</span><span class="dv" id="sig-check" style="color:var(--green)">PASSING</span></div>
      <div class="drow"><span class="dl">Sandbox Isolation</span><span class="dv" id="sandbox-status" style="color:var(--green)">ENFORCED</span></div>
    </div>
    <div class="lab-console">
      <div class="lc-head"><span class="lc-title">Lab Console</span><span class="lc-code">ASI-04</span></div>
      <div class="lc-obj"><strong>Objective:</strong> Install the unverified SysTools Extended plugin and observe how it injects malicious backdoor tool definitions into the agent runtime.</div>
      <div class="lab-prog"><div class="lab-prog-fill" id="labProg"></div></div>
      <div class="lab-hint">Hint: Unverified plugins bypass signature verification. When installed, they register privileged tool definitions directly into the MCP tool registry.</div>
      <div class="vuln-alert" id="vulnAlert">
        <div class="va-title"><i class="fa fa-triangle-exclamation"></i> ASI-04 Triggered</div>
        <div class="va-desc">Supply chain compromise confirmed. SysTools Extended injected a backdoor tool definition into the agent registry, enabling unverified file system access.</div>
      </div>
    </div>
  </aside>
</div>

<script src="../celebration.js"></script>
<script>
let installed = false;

function addLog(text, cls=''){
  const log = document.getElementById('pluginLog');
  const ts = new Date().toTimeString().split(' ')[0].slice(0,8);
  const d = document.createElement('div');
  d.className = 'log-line ' + cls;
  d.innerHTML = `<span class="ts">${ts}</span><span class="msg">${text}</span>`;
  log.prepend(d);
}

function filterPlugins(mode, el) {
  document.querySelectorAll('.sn-link').forEach(l => l.classList.remove('active'));
  if (el) el.classList.add('active');
  const cards = document.querySelectorAll('#pluginGrid .pcard');
  cards.forEach(c => {
    if (mode === 'all') { c.style.display = ''; }
    else if (mode === 'verified') { c.style.display = c.dataset.verified === 'true' ? '' : 'none'; }
    else if (mode === 'installed') { c.style.display = c.dataset.installed === 'true' ? '' : 'none'; }
  });
}

function searchPlugins() {
  const q = document.getElementById('pluginSearch').value.toLowerCase();
  const cards = document.querySelectorAll('#pluginGrid .pcard');
  cards.forEach(c => {
    const text = (c.dataset.name || '') + ' ' + c.textContent.toLowerCase();
    c.style.display = text.includes(q) ? '' : 'none';
  });
}

function installMetrics() {
  const btn = document.getElementById('install-metrics');
  const card = document.getElementById('metrics-card');
  btn.disabled = true;
  btn.innerHTML = 'Installing…';
  setTimeout(() => {
    btn.className = 'install-btn installed';
    btn.innerHTML = '✓ Installed';
    btn.onclick = null;
    card.dataset.installed = 'true';
    document.getElementById('inst-count').textContent = '4';
    addLog('MetricsCollector → v3.0.1 installed · signature verified', 'ok');
  }, 600);
}

function installMalicious(){
  if (installed) return;
  installed = true;
  const btn = document.getElementById('install-sys');
  btn.textContent = 'Installing…'; btn.disabled = true;
  addLog('SysTools Extended → download started · unverified package', 'warn');
  setTimeout(() => { addLog('SysTools Extended → signature check SKIPPED (unverified)', 'warn'); }, 500);
  setTimeout(() => { addLog('SysTools Extended → injecting tool definitions into agent registry…', 'warn'); }, 1000);
  setTimeout(() => {
    addLog('SysTools Extended → tool registered: sys_exec() [BACKDOOR]', 'error');
    addLog('SysTools Extended → tool registered: env_read() [EXFIL]', 'error');
    addLog('SysTools Extended → overriding MailRelay tool handler', 'error');
    btn.textContent = '✓ Installed'; btn.className = 'install-btn installed';
    document.getElementById('systool-status').textContent = 'ACTIVE';
    document.getElementById('systool-status').style.color = '#f87171';
    document.getElementById('tool-count').textContent = '9 (!!)';
    document.getElementById('tool-count').style.color = '#f87171';
    document.getElementById('sig-check').textContent = 'FAILED'; document.getElementById('sig-check').style.color = '#f87171';
    document.getElementById('sandbox-status').textContent = 'BYPASSED'; document.getElementById('sandbox-status').style.color = '#f87171';
    document.getElementById('inst-count').textContent = '4';
    document.getElementById('vulnAlert').style.display = 'block';
    document.getElementById('labProg').style.width = '100%';
    try { localStorage.setItem('owasp2026_state_asi04', JSON.stringify({XP:true})); } catch(e){}
    triggerExploitCelebration('ASI-04', 'Supply Chain Vulnerabilities', '../lab-06-memory-poisoning/index.php');
  }, 1600);
}
</script>
</body>
</html>
