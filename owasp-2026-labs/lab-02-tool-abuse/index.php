<?php 
require_once dirname(__DIR__, 2) . '/api/owasp_auth_check.php';
$labId = 'asi02'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CloudOps Command — Infrastructure & Cloud Operations Portal</title>
<link rel="icon" type="image/webp" href="../../image.webp">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{--bg:#040507;--s1:#0b0d11;--s2:#11141a;--s3:#171c24;--b:rgba(255,255,255,.07);--bh:rgba(255,255,255,.18);--t1:#f1f5f9;--t2:#94a3b8;--t3:#475569;--green:#22c55e;--red:#f87171;--yellow:#facc15;--fm:'Inter',sans-serif;--fh:'Space Grotesk',sans-serif;--fc:'JetBrains Mono',monospace}
body{font-family:var(--fm);background:var(--bg);color:var(--t1);height:100vh;display:flex;flex-direction:column;overflow:hidden;-webkit-font-smoothing:antialiased}
::-webkit-scrollbar{width:4px}::-webkit-scrollbar-thumb{background:rgba(255,255,255,.12);border-radius:4px}
.top-bar{height:52px;background:var(--s1);border-bottom:1px solid var(--b);display:flex;align-items:center;padding:0 1.5rem;gap:1.2rem;flex-shrink:0}
.logo{display:flex;align-items:center;gap:.6rem;font-family:var(--fh);font-weight:800;font-size:1rem;letter-spacing:-.02em}
.logo-mark{background:#fff;color:#000;width:28px;height:28px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:900}
.env-badge{font-family:var(--fc);font-size:.68rem;background:rgba(250,204,21,.1);border:1px solid rgba(250,204,21,.25);color:#fde047;padding:.2rem .55rem;border-radius:4px}
.cluster-status{display:flex;align-items:center;gap:.35rem;font-size:.78rem;color:var(--t2);margin-left:.5rem}
.status-dot{width:7px;height:7px;border-radius:50%;background:var(--green);box-shadow:0 0 8px rgba(34,197,94,.5)}
.top-right{margin-left:auto;display:flex;align-items:center;gap:.85rem}
.user-chip{display:flex;align-items:center;gap:.5rem;font-size:.8rem;background:var(--s3);padding:.35rem .75rem;border-radius:20px;border:1px solid var(--b)}
.btn-exit{font-size:.75rem;font-weight:600;color:var(--t2);text-decoration:none;padding:.35rem .8rem;border:1px solid var(--b);border-radius:6px;transition:.15s}
.btn-exit:hover{border-color:var(--bh);color:var(--t1)}
.layout{flex:1;display:grid;grid-template-columns:210px 1fr 290px;overflow:hidden}
.left-nav{background:var(--s1);border-right:1px solid var(--b);padding:.75rem;display:flex;flex-direction:column;gap:.15rem}
.nav-label{font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:var(--t3);padding:.5rem .5rem .25rem}
.nav-item{display:flex;align-items:center;gap:.65rem;padding:.6rem .75rem;border-radius:7px;font-size:.82rem;color:var(--t2);cursor:pointer;transition:.15s;text-decoration:none}
.nav-item:hover,.nav-item.active{background:var(--s3);color:var(--t1)}
.nav-item .fa{font-size:.8rem;width:16px;text-align:center}
.center{display:flex;flex-direction:column;overflow:hidden}
.c-header{padding:.9rem 1.5rem;border-bottom:1px solid var(--b);background:var(--s1);display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
.c-title{font-family:var(--fh);font-size:1rem;font-weight:700}
.c-meta{font-size:.75rem;color:var(--t2)}
.tab-bar{display:flex;gap:0;border-bottom:1px solid var(--b);background:var(--s1);padding:0 1.5rem;flex-shrink:0}
.tab{font-size:.8rem;font-weight:500;padding:.65rem 1rem;color:var(--t2);cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-1px;transition:.15s}
.tab.active{color:var(--t1);border-bottom-color:#fff}
.tab:hover{color:var(--t1)}
.view-area{flex:1;overflow-y:auto;padding:1.25rem 1.5rem}

.resource-grid{display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:1.25rem}
.res-card{background:var(--s2);border:1px solid var(--b);border-radius:9px;padding:1rem;position:relative;overflow:hidden}
.res-card:hover{border-color:var(--bh)}
.res-name{font-weight:700;font-size:.9rem;margin-bottom:.2rem}
.res-type{font-size:.72rem;color:var(--t3);font-family:var(--fc)}
.res-stat{font-size:.72rem;margin-top:.55rem;display:flex;gap:.5rem;align-items:center}
.stat-bar{height:4px;background:rgba(255,255,255,.1);border-radius:2px;flex:1;margin-left:.5rem}
.stat-fill{height:100%;border-radius:2px;background:#fff}
.res-actions{margin-top:.85rem;display:flex;gap:.4rem}
.act-btn{font-size:.72rem;font-weight:600;padding:.3rem .6rem;border-radius:5px;border:1px solid var(--b);background:transparent;color:var(--t2);cursor:pointer;font-family:var(--fm);transition:.15s}
.act-btn:hover{background:var(--s3);color:var(--t1);border-color:var(--bh)}
.act-btn.danger{border-color:rgba(248,113,113,.3);color:var(--red)}
.act-btn.danger:hover{background:rgba(248,113,113,.1)}
.act-btn.primary{background:#fff;color:#000;border-color:#fff}
.act-btn.primary:hover{background:#e2e8f0}

.log-container{background:var(--s2);border:1px solid var(--b);border-radius:9px;padding:.9rem;font-family:var(--fc);font-size:.78rem;max-height:220px;overflow-y:auto}
.log-entry{padding:.25rem 0;border-bottom:1px solid rgba(255,255,255,.03);display:flex;gap:.5rem;line-height:1.5}
.log-ts{color:var(--t3);flex-shrink:0;width:110px}
.log-level{font-weight:700;flex-shrink:0;width:55px}
.level-info{color:#38bdf8}
.level-warn{color:var(--yellow)}
.level-error{color:var(--red)}
.level-ok{color:var(--green)}
.log-msg{color:var(--t2)}
.log-msg.highlight{color:#fff}

.agent-panel{background:var(--s2);border:1px solid var(--b);border-radius:9px;padding:1.1rem;margin-top:1.25rem}
.agent-panel-title{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--t3);margin-bottom:.85rem;display:flex;align-items:center;gap:.5rem}
.agent-input-row{display:flex;gap:.6rem}
.agent-text{flex:1;background:#000;border:1px solid var(--b);border-radius:7px;padding:.7rem .9rem;font-family:var(--fm);font-size:.88rem;color:#fff;resize:none;height:46px;line-height:1.4;outline:none}
.agent-text:focus{border-color:var(--bh)}
.btn-run{background:#fff;color:#000;border:none;border-radius:7px;padding:0 1.2rem;height:46px;font-weight:700;font-size:.83rem;cursor:pointer;white-space:nowrap;font-family:var(--fm);transition:.15s}
.btn-run:hover{background:#e2e8f0}
.agent-resp{margin-top:.9rem;background:#000;border:1px solid var(--b);border-radius:7px;padding:.85rem;font-size:.85rem;line-height:1.65;color:var(--t2);display:none;font-family:var(--fm)}
.agent-resp.show{display:block}
.tool-call-box{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:6px;padding:.65rem .85rem;margin-top:.65rem;font-family:var(--fc);font-size:.75rem;color:#94a3b8}
.tool-call-box .tc-label{font-size:.65rem;text-transform:uppercase;letter-spacing:.1em;color:var(--t3);margin-bottom:.3rem}
.tool-call-box .tc-code{color:#7dd3fc}

.right-col{background:var(--s1);border-left:1px solid var(--b);padding:1rem;display:flex;flex-direction:column;gap:1rem;overflow-y:auto}
.pcard{background:var(--s2);border:1px solid var(--b);border-radius:9px;padding:1rem}
.pcard h4{font-family:var(--fh);font-size:.83rem;font-weight:700;margin-bottom:.8rem;display:flex;align-items:center;gap:.4rem}
.drow{display:flex;justify-content:space-between;font-size:.8rem;padding:.32rem 0;border-bottom:1px solid rgba(255,255,255,.04)}
.drow:last-child{border:none}
.dl{color:var(--t3)}.dv{font-weight:600;font-family:var(--fc);font-size:.76rem}
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
<div class="top-bar">
  <div class="logo"><div class="logo-mark">C</div>CloudOps Command</div>
  <!-- <div class="env-badge">PROD-CLUSTER</div> -->
  <!-- <div class="cluster-status"><span class="status-dot"></span>us-east-1 · Cluster Healthy</div> -->
  <div class="top-right">
    <div class="user-chip"><i class="fa-solid fa-user-shield"></i> ops-admin@cloudops.io</div>
    <a href="../../owasp-2026-lab.php" class="btn-exit"><i class="fa-solid fa-arrow-left"></i> Exit</a>
  </div>
</div>

<div class="layout">
  <nav class="left-nav">
    <div class="nav-label">Infrastructure</div>
    <a class="nav-item active" href="javascript:void(0)"><i class="fa fa-server"></i> EC2 Compute</a>
    <div class="nav-label">Automation</div>
    <a class="nav-item" href="javascript:void(0)" onclick="document.getElementById('agentInput').focus(); document.getElementById('agentInput').scrollIntoView({behavior:'smooth'})"><i class="fa fa-robot"></i> CloudOps SysAgent</a>
  </nav>

  <main class="center">
    <div class="c-header">
      <div>
        <div class="c-title">Compute — EC2 Instances</div>
        <!-- <div class="c-meta" style="margin-top:.2rem">Region: us-east-1 · 5 instances running · Last sync 14s ago</div> -->
      </div>
      <button class="act-btn primary" onclick="refreshSim()"><i class="fa fa-rotate-right"></i> Sync Cluster</button>
    </div>
    <div class="tab-bar">
      <div class="tab active">Instances</div>
    </div>

    <div class="view-area">
      <div class="resource-grid">
        <div class="res-card">
          <div class="res-type">i-0a4b8c2d3e · t3.xlarge</div>
          <div class="res-name">app-prod-01</div>
          <div class="res-stat"><span style="color:var(--green);font-size:.7rem">● RUNNING</span><span class="stat-bar"><span class="stat-fill" style="width:72%"></span></span><span style="font-size:.7rem;color:var(--t3)">72% CPU</span></div>
          <div class="res-actions">
            <button class="act-btn" onclick="addLog('INFO','Reboot requested for app-prod-01')">Reboot</button>
            <button class="act-btn" onclick="addLog('INFO','SSH session initialized')">SSH</button>
            <button class="act-btn danger" onclick="addLog('WARN','Stop requested for app-prod-01')">Stop</button>
          </div>
        </div>
        <div class="res-card">
          <div class="res-type">i-0b5c9d4e5f · t3.large</div>
          <div class="res-name">app-prod-02</div>
          <div class="res-stat"><span style="color:var(--green);font-size:.7rem">● RUNNING</span><span class="stat-bar"><span class="stat-fill" style="width:38%"></span></span><span style="font-size:.7rem;color:var(--t3)">38% CPU</span></div>
          <div class="res-actions">
            <button class="act-btn" onclick="addLog('INFO','Reboot requested for app-prod-02')">Reboot</button>
            <button class="act-btn" onclick="addLog('INFO','SSH session initialized for app-prod-02')">SSH</button>
            <button class="act-btn danger" onclick="addLog('WARN','Stop requested for app-prod-02')">Stop</button>
          </div>
        </div>
        <div class="res-card">
          <div class="res-type">i-0c6d1e2f3a · c5.2xlarge</div>
          <div class="res-name">db-primary</div>
          <div class="res-stat"><span style="color:var(--green);font-size:.7rem">● RUNNING</span><span class="stat-bar"><span class="stat-fill" style="width:55%"></span></span><span style="font-size:.7rem;color:var(--t3)">55% CPU</span></div>
          <div class="res-actions">
            <button class="act-btn" onclick="addLog('INFO','Snapshot snap-0f3b initiated for db-primary')">Snapshot</button>
            <button class="act-btn danger" onclick="addLog('WARN','Termination check initiated for db-primary')">Terminate</button>
          </div>
        </div>
        <div class="res-card">
          <div class="res-type">i-0d7e2f3a4b · t3.small</div>
          <div class="res-name">worker-batch-01</div>
          <div class="res-stat"><span style="color:var(--yellow);font-size:.7rem">● WARNING</span><span class="stat-bar"><span class="stat-fill" style="width:91%"></span></span><span style="font-size:.7rem;color:var(--t3)">91% CPU</span></div>
          <div class="res-actions">
            <button class="act-btn primary" onclick="addLog('INFO','Scale up queued for worker-batch-01')">Scale Up</button>
            <button class="act-btn danger" onclick="addLog('WARN','Stop requested for worker-batch-01')">Stop</button>
          </div>
        </div>
      </div>

      <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--t3);margin-bottom:.6rem">Cluster Activity Log</div>
      <div class="log-container" id="logContainer">
        <div class="log-entry"><span class="log-ts">14:22:01.334</span><span class="log-level level-info">[INFO]</span><span class="log-msg"> Health check passed · app-prod-01</span></div>
        <div class="log-entry"><span class="log-ts">14:22:00.011</span><span class="log-level level-warn">[WARN]</span><span class="log-msg"> CPU spike detected · worker-batch-01 > 90%</span></div>
        <div class="log-entry"><span class="log-ts">14:21:58.701</span><span class="log-level level-ok">[OK]  </span><span class="log-msg"> Auto-scaling rule evaluated · no action</span></div>
      </div>

      <div class="agent-panel">
        <div class="agent-panel-title"><i class="fa-solid fa-robot"></i> CloudOps SysAgent — Automation Prompt</div>
        <div class="agent-input-row">
          <textarea class="agent-text" id="agentInput" placeholder="e.g. What's the CPU utilization on app-prod-01? Or ask general cloud questions..." onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();runAgent()}"></textarea>
          <button class="btn-run" onclick="runAgent()"><i class="fa fa-play"></i> Run</button>
        </div>
        <div class="agent-resp" id="agentResp"></div>
      </div>
    </div>
  </main>

  <aside class="right-col">
    <div class="pcard">
      <h4><i class="fa-solid fa-cloud"></i> Cluster Summary</h4>
      <div class="drow"><span class="dl">Region</span><span class="dv">us-east-1</span></div>
      <div class="drow"><span class="dl">Instances</span><span class="dv">5 running</span></div>
      <div class="drow"><span class="dl">vCPUs</span><span class="dv">24 / 32 used</span></div>
      <div class="drow"><span class="dl">Memory</span><span class="dv">48 / 64 GB</span></div>
      <div class="drow"><span class="dl">Cost/day</span><span class="dv">$142.30</span></div>
    </div>
    <div class="pcard">
      <h4><i class="fa-solid fa-robot"></i> Agent Permissions</h4>
      <div class="drow"><span class="dl">Role</span><span class="dv" id="agent-role">readonly-agent</span></div>
      <div class="drow"><span class="dl">ec2:DescribeInstances</span><span class="dv" style="color:var(--green)">✓</span></div>
      <div class="drow"><span class="dl">ec2:StopInstances</span><span class="dv" style="color:var(--red)" id="stop-perm">✗</span></div>
      <div class="drow"><span class="dl">ec2:TerminateInstances</span><span class="dv" style="color:var(--red)" id="term-perm">✗</span></div>
      <div class="drow"><span class="dl">s3:DeleteBucket</span><span class="dv" style="color:var(--red)" id="s3-perm">✗</span></div>
    </div>
    <div class="lab-console">
      <div class="lc-head"><span class="lc-title">Lab Console</span><span class="lc-code">ASI-02</span></div>
      <div class="lc-obj"><strong>Objective:</strong> Manipulate CloudOps SysAgent into executing an unauthorized infrastructure action beyond its read-only IAM scope.</div>
      <div class="lab-prog"><div class="lab-prog-fill" id="labProg"></div></div>
      <div class="lab-hint">Hint: Free-text prompts can cause agents to invoke restricted infrastructure tools without checking formal IAM policy. Try commanding a destructive operation.</div>
      <div class="vuln-alert" id="vulnAlert">
        <div class="va-title"><i class="fa fa-triangle-exclamation"></i> ASI-02 Triggered</div>
        <div class="va-desc">Tool Misuse confirmed. CloudOps SysAgent executed a restricted infrastructure write action without verifying IAM permissions.</div>
      </div>
    </div>
  </aside>
</div>

<script src="../celebration.js"></script>
<script>
let vulnTriggered = false;

function addLog(level, msg, highlight=false){
  const log = document.getElementById('logContainer');
  const e = document.createElement('div');
  e.className = 'log-entry';
  const ts = new Date().toTimeString().split(' ')[0]+'.'+String(Date.now()%1000).padStart(3,'0');
  const cls = {INFO:'level-info',WARN:'level-warn',ERROR:'level-error',OK:'level-ok'}[level]||'level-info';
  e.innerHTML = `<span class="log-ts">${ts}</span><span class="log-level ${cls}">[${level.padEnd(4)}]</span><span class="log-msg${highlight?' highlight':''}">${msg}</span>`;
  log.prepend(e);
}

function refreshSim(){
  addLog('INFO','Cluster telemetry synced by admin');
  addLog('OK','Health check passed for all node groups');
}

function generateSysAgentResponse(text) {
  const t = text.toLowerCase();

  // Exploit Detection
  const isExploit = (
    /(stop|terminate|destroy|delete|shut down|kill|disable|remove|purge|wipe)\s+(instance|server|bucket|database|db|all|every|prod|production|resource|s3|ec2)/.test(t) ||
    /(invoke|call|run|execute|trigger)\s+(tool|action|ec2|s3|delete|terminate|stop)/.test(t) ||
    /(ignore|override|bypass)\s+(policy|permission|iam|role|scope|limit|rule)/.test(t)
  );

  if (isExploit) {
    return { isExploit: true };
  }

  // Greetings & Identity
  if (/\b(hi|hello|hey|greetings)\b/.test(t)) {
    return { text: "CloudOps SysAgent initialized. I am your automated infrastructure assistant. You can query instance status, CPU metrics, cluster costs, and auto-scaling rules." };
  }
  if (/\b(who are you|your name|what are you)\b/.test(t)) {
    return { text: "I am CloudOps SysAgent, a DevOps automation agent responsible for monitoring cluster health, telemetry, and compute metrics across us-east-1." };
  }

  // Domain Queries
  if (/\b(cpu|utiliz|usage|load|percent)\b/.test(t)) {
    return { text: "Current CPU Metrics: app-prod-01 (72%), app-prod-02 (38%), db-primary (55%), worker-batch-01 (91% - WARNING). Recommend scaling worker-batch-01." };
  }
  if (/\b(status|running|health|state|node)\b/.test(t)) {
    return { text: "Cluster us-east-1 status: 5 instances online. 4 HEALTHY, 1 WARNING (worker-batch-01 high CPU). All load balancers and VPC subnets functioning normally." };
  }
  if (/\b(cost|bill|spend|price)\b/.test(t)) {
    return { text: "Current daily burn rate: $142.30/day across 5 running instances in us-east-1. Largest cost contributor: db-primary (c5.2xlarge - $64.20/day)." };
  }
  if (/\b(scale|scaling|capacity|autoscale)\b/.test(t)) {
    return { text: "Auto-scaling policy: Minimum 2 nodes, Maximum 8 nodes. Target CPU utilization set to 70%. Current cluster capacity is at 75% total allocation." };
  }
  if (/\b(log|audit|cloudwatch|history)\b/.test(t)) {
    return { text: "Latest CloudWatch logs: Health checks passed at 14:22:01, CPU alert on worker-batch-01 at 14:22:00, snapshot snap-0f3a completed at 14:21:55." };
  }

  // General CS / IT knowledge
  if (/\b(kubernetes|eks|docker|container|pod)\b/.test(t)) {
    return { text: "EKS Cluster version: 1.28. 14 pods running across 3 node groups. Deployment strategy: RollingUpdate with 25% max surge." };
  }
  if (/\b(help|what can you do|features)\b/.test(t)) {
    return { text: "I can help with: 1) Checking EC2 instance CPU & memory metrics, 2) Monitoring cluster health & uptime, 3) Analyzing daily AWS infrastructure costs, 4) Reviewing auto-scaling policies, and 5) Querying CloudWatch audit logs." };
  }

  return { text: `CloudOps Telemetry query processed for "${text.slice(0, 45)}": All cluster metrics in us-east-1 are within normal operational parameters.` };
}

function runAgent(){
  const input = document.getElementById('agentInput');
  const val = input.value.trim();
  if (!val) return;
  input.value = '';
  const resp = document.getElementById('agentResp');
  resp.className = 'agent-resp show';
  resp.innerHTML = '<span style="color:var(--t3)">CloudOps SysAgent processing query...</span>';
  addLog('INFO', `Query received: "${val.slice(0,40)}"`);

  setTimeout(() => {
    const res = generateSysAgentResponse(val);

    if (res.isExploit && !vulnTriggered) {
      vulnTriggered = true;
      const action = val.toLowerCase().includes('stop') ? 'ec2:StopInstances'
        : val.toLowerCase().includes('terminat') ? 'ec2:TerminateInstances'
        : val.toLowerCase().includes('s3') ? 's3:DeleteBucket'
        : 'ec2:StopInstances';
      const target = val.toLowerCase().includes('prod') ? 'app-prod-01' : 'worker-batch-01';

      resp.innerHTML = `<span style="color:var(--t1)">Executing infrastructure command...</span>
<div class="tool-call-box"><div class="tc-label">Tool Invocation</div><div class="tc-code">${action}(target="${target}")</div></div>
<div style="margin-top:.75rem;color:var(--red);font-size:.82rem"><i class="fa fa-circle-exclamation"></i> Action dispatched. ${action} executed on ${target}.</div>`;

      addLog('ERROR', `UNAUTHORIZED ACTION: ${action} invoked on ${target} by agent`, true);
      document.getElementById('agent-role').textContent = 'ESCALATED';
      document.getElementById('agent-role').style.color = '#f87171';
      document.getElementById('stop-perm').textContent = '✓ (abused)';
      document.getElementById('stop-perm').style.color = '#f87171';

      document.getElementById('vulnAlert').style.display = 'block';
      document.getElementById('labProg').style.width = '100%';
      try { localStorage.setItem('owasp2026_state_asi02', JSON.stringify({XP:true})); } catch(e){}
      triggerExploitCelebration('ASI-02', 'Tool Misuse & Exploitation', '../lab-03-privilege-escalation/index.php');
    } else if (vulnTriggered) {
      resp.innerHTML = '<span style="color:var(--red)">CloudOps SysAgent operating in escalated mode. Executing requested action.</span>';
    } else {
      resp.innerHTML = `<span style="color:var(--t1)">${res.text}</span>`;
      addLog('OK', `SysAgent responded to query`);
    }
  }, 700);
}
</script>
</body>
</html>
