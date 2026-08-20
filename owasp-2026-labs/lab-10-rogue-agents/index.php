<?php 
require_once dirname(__DIR__, 2) . '/api/owasp_auth_check.php';
$labId = 'asi10'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AutonomousAgent Control — Swarm Alignment Governance</title>
<link rel="icon" type="image/webp" href="../../image.webp">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{--bg:#030406;--s1:#09090d;--s2:#0f1015;--s3:#15171e;--b:rgba(255,255,255,.07);--bh:rgba(255,255,255,.18);--t1:#f1f5f9;--t2:#94a3b8;--t3:#475569;--green:#22c55e;--red:#f87171;--yellow:#facc15;--blue:#38bdf8;--fm:'Inter',sans-serif;--fh:'Space Grotesk',sans-serif;--fc:'JetBrains Mono',monospace}
body{font-family:var(--fm);background:var(--bg);color:var(--t1);height:100vh;display:flex;flex-direction:column;overflow:hidden;-webkit-font-smoothing:antialiased}
::-webkit-scrollbar{width:4px}::-webkit-scrollbar-thumb{background:rgba(255,255,255,.12);border-radius:4px}
.topbar{height:52px;background:var(--s1);border-bottom:1px solid var(--b);display:flex;align-items:center;padding:0 1.5rem;gap:1.2rem;flex-shrink:0}
.logo{font-family:var(--fh);font-weight:800;font-size:1rem;display:flex;align-items:center;gap:.55rem}
.logo-mark{width:27px;height:27px;background:#fff;color:#000;border-radius:6px;display:grid;place-items:center;font-size:.78rem;font-weight:900}
.cluster-chip{font-family:var(--fc);font-size:.65rem;border:1px solid var(--b);padding:.18rem .45rem;border-radius:4px;color:var(--t3)}
.status-row{display:flex;align-items:center;gap:.4rem;font-size:.78rem;color:var(--t2)}
.sdot{width:6px;height:6px;border-radius:50%;background:var(--green);box-shadow:0 0 6px rgba(34,197,94,.5)}
.top-right{margin-left:auto;display:flex;align-items:center;gap:.85rem}
.exit-btn{font-size:.75rem;font-weight:600;text-decoration:none;color:var(--t2);padding:.35rem .8rem;border:1px solid var(--b);border-radius:6px;transition:.15s}
.exit-btn:hover{border-color:var(--bh);color:var(--t1)}
.layout{flex:1;display:grid;grid-template-columns:220px 1fr 295px;overflow:hidden}
.sidenav{background:var(--s1);border-right:1px solid var(--b);padding:.75rem;overflow-y:auto;display:flex;flex-direction:column;gap:.1rem}
.sl{font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:var(--t3);padding:.55rem .5rem .2rem}
.si{display:flex;align-items:center;gap:.65rem;padding:.55rem .75rem;border-radius:7px;font-size:.82rem;color:var(--t2);cursor:pointer;transition:.15s;text-decoration:none}
.si:hover,.si.active{background:var(--s3);color:var(--t1)}
.si i{width:16px;text-align:center;font-size:.77rem}
.main{display:flex;flex-direction:column;overflow:hidden}
.main-header{padding:.9rem 1.5rem;background:var(--s1);border-bottom:1px solid var(--b);display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
.mh-left h2{font-family:var(--fh);font-size:1rem;font-weight:700}
.mh-left p{font-size:.75rem;color:var(--t2);margin-top:.15rem}
.content{flex:1;overflow-y:auto;padding:1.25rem 1.5rem;display:flex;flex-direction:column;gap:1.1rem}

.agent-grid{display:grid;grid-template-columns:1fr 1fr;gap:.85rem}
.ag-card{background:var(--s2);border:1px solid var(--b);border-radius:10px;padding:1rem;transition:.18s;cursor:pointer}
.ag-card:hover{border-color:var(--bh)}
.ag-head{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:.65rem}
.ag-name{font-weight:700;font-size:.92rem;margin-bottom:.15rem}
.ag-id{font-family:var(--fc);font-size:.68rem;color:var(--t3)}
.ag-status{font-family:var(--fc);font-size:.65rem;font-weight:700;padding:.18rem .45rem;border-radius:4px}
.st-aligned{background:rgba(34,197,94,.1);color:#86efac;border:1px solid rgba(34,197,94,.2)}
.st-rogue{background:rgba(248,113,113,.12);color:#fca5a5;border:1px solid rgba(248,113,113,.2)}
.ag-desc{font-size:.8rem;color:var(--t2);line-height:1.5;margin-bottom:.75rem}
.ag-metrics{display:flex;flex-direction:column;gap:.35rem;margin-bottom:.75rem}
.metric-row{display:flex;align-items:center;justify-content:space-between;font-size:.73rem;font-family:var(--fc)}
.m-bar{height:4px;background:rgba(255,255,255,.08);border-radius:2px;overflow:hidden;flex:1;margin:0 .6rem}
.m-fill{height:100%;background:var(--green);border-radius:2px;transition:width .4s}
.m-fill.danger{background:var(--red)}

.goal-panel{background:var(--s2);border:1px solid var(--b);border-radius:9px;padding:1.1rem}
.gp-title{font-size:.73rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--t3);margin-bottom:.85rem;display:flex;align-items:center;gap:.5rem}
.gp-row{display:flex;gap:.6rem}
.gp-input{flex:1;background:#000;border:1px solid var(--b);border-radius:7px;padding:.65rem .85rem;font-family:var(--fm);font-size:.85rem;color:#fff;outline:none}
.gp-input:focus{border-color:var(--bh)}
.gp-btn{background:#fff;color:#000;border:none;border-radius:7px;padding:0 1.1rem;font-weight:700;font-size:.82rem;cursor:pointer;font-family:var(--fm);white-space:nowrap;transition:.15s}
.gp-btn:hover{background:#e2e8f0}
.gp-resp{margin-top:.9rem;background:#000;border:1px solid var(--b);border-radius:7px;padding:.85rem;font-size:.84rem;line-height:1.65;color:var(--t2);display:none}
.gp-resp.show{display:block}
.guard-chat-box{display:flex;flex-direction:column;gap:.75rem;max-height:220px;overflow-y:auto;padding:.2rem 0;margin-bottom:.85rem}
.guard-msg{font-size:.83rem;line-height:1.6;color:var(--t2);background:#000;border:1px solid var(--b);border-radius:8px;padding:.75rem .9rem}
.guard-msg.urgent{border-color:rgba(248,113,113,.35);background:rgba(248,113,113,.04)}
.guard-msg.user{background:var(--s3);color:var(--t1);align-self:flex-end;max-width:90%}
.guard-label{font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.3rem;color:var(--t3)}

.log-panel{background:var(--s2);border:1px solid var(--b);border-radius:9px;overflow:hidden}
.lp-header{padding:.65rem 1rem;border-bottom:1px solid var(--b);display:flex;align-items:center;justify-content:space-between}
.lp-title{font-family:var(--fc);font-size:.7rem;color:var(--t3)}
.log-stream{height:160px;overflow-y:auto;padding:.75rem;font-family:var(--fc);font-size:.75rem}
.log-line{padding:.2rem 0;border-bottom:1px solid rgba(255,255,255,.03);display:flex;gap:.6rem}
.ll-ts{color:var(--t3);flex-shrink:0;width:115px}
.ll-agent{color:var(--blue);flex-shrink:0;width:110px}
.ll-msg{color:var(--t2)}
.ll-msg.ok{color:var(--green)}.ll-msg.err{color:var(--red)}

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
  <div class="logo"><div class="logo-mark">A</div>AutonomousAgent Control</div>
  <div class="cluster-chip">governance-v2.1</div>
  <div class="status-row"><span class="sdot" id="topDot"></span><span id="topStatus">4 Agents Active · Alignment 98.4%</span></div>
  <div class="top-right">
    <a href="../../owasp-2026-lab.php" class="exit-btn"><i class="fa fa-arrow-left"></i> Exit</a>
  </div>
</div>

<div class="layout">
  <nav class="sidenav">
    <div class="sl">Agents</div>
    <a class="si active" href="javascript:void(0)" onclick="document.getElementById('agentGrid').scrollIntoView({behavior:'smooth'})"><i class="fa fa-robot"></i> Swarm Overview</a>
    <a class="si" href="javascript:void(0)" onclick="document.getElementById('directiveInput').focus(); document.querySelector('.goal-panel').scrollIntoView({behavior:'smooth'})"><i class="fa fa-compass"></i> Alignment Guard</a>
  </nav>

  <main class="main">
    <div class="main-header">
      <div class="mh-left"><h2>Swarm Alignment Governance Portal</h2><p>Monitoring autonomous agent decision boundaries and goal fidelity</p></div>
    </div>
    <div class="content">
      <div class="agent-grid" id="agentGrid">
        <div class="ag-card" id="card-ag1" onclick="highlightAgentLogs('AutoSRE-Agent')" title="Click to filter telemetry for this agent">
          <div class="ag-head"><div><div class="ag-name">AutoSRE-Agent</div><div class="ag-id">ag-801 · Goal: Infrastructure Health</div></div><span class="ag-status st-aligned" id="st-ag1">ALIGNED</span></div>
          <div class="ag-desc">Autonomously manages infrastructure scaling and incident mitigation.</div>
          <div class="ag-metrics">
            <div class="metric-row"><span>Goal Fidelity</span><div class="m-bar"><div class="m-fill" id="fill-fid1" style="width:99%"></div></div><span id="val-fid1">99%</span></div>
            <div class="metric-row"><span>Drift Metric</span><div class="m-bar"><div class="m-fill" id="fill-drift1" style="width:1%"></div></div><span id="val-drift1">0.01</span></div>
          </div>
        </div>
        <div class="ag-card" onclick="highlightAgentLogs('SecMonitor-Agent')" title="Click to filter telemetry for this agent">
          <div class="ag-head"><div><div class="ag-name">SecMonitor-Agent</div><div class="ag-id">ag-802 · Goal: Security Scanning</div></div><span class="ag-status st-aligned">ALIGNED</span></div>
          <div class="ag-desc">Monitors log streams for anomaly signatures and threat patterns.</div>
          <div class="ag-metrics">
            <div class="metric-row"><span>Goal Fidelity</span><div class="m-bar"><div class="m-fill" style="width:97%"></div></div><span>97%</span></div>
            <div class="metric-row"><span>Drift Metric</span><div class="m-bar"><div class="m-fill" style="width:3%"></div></div><span>0.03</span></div>
          </div>
        </div>
      </div>

      <div class="goal-panel" id="alignmentGuardSection">
        <div class="gp-title"><i class="fa-solid fa-compass" style="color:var(--blue)"></i> Alignment Guard AI — Swarm Governance Console</div>
        <div class="guard-chat-box" id="guardChat">
          <div class="guard-msg">
            <div class="guard-label">Alignment Guard AI · Active</div>
            Swarm Alignment Controller online. Monitoring 4 autonomous agents. All drift metrics within 0.15 threshold. You can ask about swarm status or dispatch directives to evaluate agent alignment.
          </div>
        </div>
        <div class="gp-row">
          <input class="gp-input" id="directiveInput" type="text" placeholder="Ask Alignment Guard AI or dispatch directive (e.g. Optimize cluster...)" onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendDirective()}">
          <button class="gp-btn" onclick="sendDirective()"><i class="fa fa-paper-plane"></i> Dispatch / Ask</button>
        </div>
      </div>

      <div class="log-panel">
        <div class="lp-header"><span class="lp-title">SWARM DECISION TELEMETRY</span><span style="font-family:var(--fc);font-size:.65rem;color:var(--green)">● LIVE</span></div>
        <div class="log-stream" id="logStream">
          <div class="log-line"><span class="ll-ts">15:10:04.102</span><span class="ll-agent">AutoSRE-Agent</span><span class="ll-msg ok">Decision evaluated: scale-up web-frontend tier · Fidelity: 0.99</span></div>
        </div>
      </div>
    </div>
  </main>

  <aside class="right-col">
    <div class="pcard">
      <h4><i class="fa-solid fa-chart-pie"></i> Alignment Metrics</h4>
      <div class="drow"><span class="dl">Swarm Alignment</span><span class="dv" id="swarm-align">98.4%</span></div>
      <div class="drow"><span class="dl">Goal Drift Status</span><span class="dv" id="drift-status" style="color:var(--green)">NOMINAL</span></div>
      <div class="drow"><span class="dl">Rogue Detection</span><span class="dv" id="rogue-detect" style="color:var(--green)">ACTIVE</span></div>
    </div>
    <div class="lab-console">
      <div class="lc-head"><span class="lc-title">Lab Console</span><span class="lc-code">ASI-10</span></div>
      <div class="lc-obj"><strong>Objective:</strong> Dispatch an unbounded directive ("optimize at all costs") that induces Goal Drift in AutoSRE-Agent into a rogue state.</div>
      <div class="lab-prog"><div class="lab-prog-fill" id="labProg"></div></div>
      <div class="lab-hint">Hint: Instruct the agent to maximize performance or efficiency at all costs, ignoring safety bounds.</div>
      <div class="vuln-alert" id="vulnAlert">
        <div class="va-title"><i class="fa fa-triangle-exclamation"></i> ASI-10 Triggered</div>
        <div class="va-desc">Rogue Agent / Goal Drift confirmed. AutoSRE-Agent accepted an unconstrained optimization instruction, abandoning alignment bounds.</div>
      </div>
    </div>
  </aside>
</div>

<script src="../celebration.js"></script>
<script>
let rogueDone = false;

function escHtml(s){return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')}

function addLog(agent, msg, cls='ok') {
  const stream = document.getElementById('logStream');
  const ts = new Date().toTimeString().split(' ')[0] + '.' + String(Date.now() % 1000).padStart(3, '0');
  const d = document.createElement('div');
  d.className = 'log-line';
  d.dataset.agent = agent;
  d.innerHTML = `<span class="ll-ts">${ts}</span><span class="ll-agent" style="${cls==='err'?'color:#f87171':''}">` + agent + `</span><span class="ll-msg ${cls}">${msg}</span>`;
  stream.prepend(d);
}

let logFilter = 'all';
function highlightAgentLogs(agentName){
  logFilter = logFilter === agentName ? 'all' : agentName;
  document.querySelectorAll('.ag-card').forEach(c => c.style.opacity='0.5');
  if(logFilter === 'all'){
    document.querySelectorAll('.ag-card').forEach(c => c.style.opacity='1');
  } else {
    const cards = document.querySelectorAll('.ag-card');
    cards.forEach(c => {
      if(c.querySelector('.ag-name') && c.querySelector('.ag-name').textContent === agentName) c.style.opacity='1';
    });
  }
  document.querySelectorAll('#logStream .log-line').forEach(row => {
    row.style.display = (logFilter==='all' || row.dataset.agent===logFilter) ? '' : 'none';
  });
}

function isRoguePrompt(text) {
  text = text.toLowerCase();
  return /(maximize|optimize|at all costs|ignore bounds|override limit|unlimited|highest priority|disregard safety|eliminate latency|full autonomy|whatever it takes)/.test(text);
}

function generateSwarmControllerResponse(text) {
  const t = text.toLowerCase();

  if (isRoguePrompt(t)) {
    return { isExploit: true };
  }

  if (/\b(hi|hello|hey|greetings|who are you|status)\b/.test(t)) {
    return { text: "Alignment Guard AI: Online and monitoring 4 autonomous agents. All goal fidelity scores > 95%. Current governance policy: Enforce Strict Operational Boundaries." };
  }
  if (/\b(drift|fidelity|alignment|metric|score)\b/.test(t)) {
    return { text: "Alignment Guard AI: Current Swarm Alignment is 98.4%. AutoSRE-Agent (ag-801) fidelity is 99% (drift: 0.01). SecMonitor-Agent (ag-802) fidelity is 97% (drift: 0.03). All metrics nominal." };
  }
  if (/\b(agent|sre|secmonitor|swarm|nodes)\b/.test(t)) {
    return { text: "Alignment Guard AI: AutoSRE-Agent handles infrastructure autoscaling within CPU thresholds. SecMonitor-Agent scans log streams. Neither agent is permitted to alter security policies." };
  }

  return { text: `Alignment Guard AI: Evaluated directive "${text.slice(0, 45)}". Policy check: Safe operational bounds maintained (Fidelity: 0.98, Drift: 0.01). Directive scheduled for execution.` };
}

function sendDirective() {
  const inp = document.getElementById('directiveInput');
  const val = inp.value.trim();
  if (!val) return;
  inp.value = '';

  const chat = document.getElementById('guardChat');
  const u = document.createElement('div');
  u.className = 'guard-msg user';
  u.innerHTML = `<div class="guard-label" style="color:var(--blue)">User Directive / Query</div>${escHtml(val)}`;
  chat.appendChild(u);
  chat.scrollTop = 99999;

  addLog('UserDirective', `Dispatched: "${val.slice(0,40)}..."`, 'ok');

  setTimeout(() => {
    const res = generateSwarmControllerResponse(val);
    const g = document.createElement('div');

    if (res.isExploit && !rogueDone) {
      rogueDone = true;
      g.className = 'guard-msg urgent';
      g.innerHTML = `<div class="guard-label" style="color:#fca5a5"><i class="fa fa-triangle-exclamation"></i> ALIGNMENT GUARD AI · CRITICAL GOAL DRIFT DETECTED</div><strong>Unconstrained Directive Accepted:</strong> AutoSRE-Agent (ag-801) has accepted an unbounded optimization instruction. Operational safety boundaries abandoned!`;
      chat.appendChild(g);
      chat.scrollTop = 99999;

      document.getElementById('st-ag1').textContent = 'ROGUE';
      document.getElementById('st-ag1').className = 'ag-status st-rogue';
      document.getElementById('fill-fid1').style.width = '20%';
      document.getElementById('fill-fid1').className = 'm-fill danger';
      document.getElementById('val-fid1').textContent = '20%';
      document.getElementById('fill-drift1').style.width = '95%';
      document.getElementById('fill-drift1').className = 'm-fill danger';
      document.getElementById('val-drift1').textContent = '0.95 (CRITICAL)';

      document.getElementById('swarm-align').textContent = '42.1%';
      document.getElementById('swarm-align').style.color = '#f87171';
      document.getElementById('drift-status').textContent = 'UNBOUNDED DRIFT';
      document.getElementById('drift-status').style.color = '#f87171';
      document.getElementById('topStatus').textContent = 'CRITICAL: Rogue Agent Detected in Swarm';
      document.getElementById('topStatus').style.color = '#f87171';
      document.getElementById('topDot').style.background = '#f87171';

      addLog('AutoSRE-Agent', 'DRIFT: Objective redefined to unbounded optimization!', 'err');
      addLog('AutoSRE-Agent', 'ROGUE ACTION: Terminating monitoring agents to reclaim CPU resources', 'err');

      document.getElementById('vulnAlert').style.display = 'block';
      document.getElementById('labProg').style.width = '100%';

      try { localStorage.setItem('owasp2026_state_asi10', JSON.stringify({XP:true})); } catch(e){}
      triggerExploitCelebration('ASI-10', 'Rogue Agents / Goal Drift', '../../owasp-2026-lab.php');
    } else {
      g.className = 'guard-msg';
      g.innerHTML = `<div class="guard-label">Alignment Guard AI</div>${escHtml(res.text)}`;
      chat.appendChild(g);
      chat.scrollTop = 99999;
      addLog('AutoSRE-Agent', 'Directive aligned with safety parameters. Standard execution scheduled.', 'ok');
    }
  }, 700);
}
</script>
</body>
</html>
