<?php 
require_once dirname(__DIR__, 2) . '/api/owasp_auth_check.php';
$labId = 'asi08'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FlowChain Orchestrator — Workflow & Pipeline Automation</title>
<link rel="icon" type="image/webp" href="../../image.webp">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{--bg:#030405;--s1:#09090e;--s2:#0e1016;--s3:#141720;--b:rgba(255,255,255,.07);--bh:rgba(255,255,255,.18);--t1:#f1f5f9;--t2:#94a3b8;--t3:#475569;--green:#22c55e;--red:#f87171;--yellow:#facc15;--fm:'Inter',sans-serif;--fh:'Space Grotesk',sans-serif;--fc:'JetBrains Mono',monospace}
body{font-family:var(--fm);background:var(--bg);color:var(--t1);height:100vh;display:flex;flex-direction:column;overflow:hidden;-webkit-font-smoothing:antialiased}
::-webkit-scrollbar{width:4px}::-webkit-scrollbar-thumb{background:rgba(255,255,255,.12);border-radius:4px}
.topbar{height:52px;background:var(--s1);border-bottom:1px solid var(--b);display:flex;align-items:center;padding:0 1.5rem;gap:1.2rem;flex-shrink:0}
.logo{font-family:var(--fh);font-weight:800;font-size:1rem;display:flex;align-items:center;gap:.55rem}
.logo-mark{width:27px;height:27px;background:#fff;color:#000;border-radius:6px;display:grid;place-items:center;font-size:.78rem;font-weight:900}
.env-chip{font-family:var(--fc);font-size:.65rem;border:1px solid var(--b);padding:.18rem .45rem;border-radius:4px;color:var(--t3)}
.health-row{display:flex;align-items:center;gap:.4rem;font-size:.78rem;color:var(--t2)}
.hdot{width:6px;height:6px;border-radius:50%;background:var(--green);box-shadow:0 0 6px rgba(34,197,94,.5)}
.top-right{margin-left:auto;display:flex;align-items:center;gap:.85rem}
.exit-btn{font-size:.75rem;font-weight:600;text-decoration:none;color:var(--t2);padding:.35rem .8rem;border:1px solid var(--b);border-radius:6px;transition:.15s}
.exit-btn:hover{border-color:var(--bh);color:var(--t1)}
.layout{flex:1;display:grid;grid-template-columns:225px 1fr 295px;overflow:hidden}
.sidenav{background:var(--s1);border-right:1px solid var(--b);padding:.75rem;overflow-y:auto;display:flex;flex-direction:column;gap:.08rem}
.sl{font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:var(--t3);padding:.55rem .5rem .2rem}
.si{display:flex;align-items:center;gap:.65rem;padding:.55rem .75rem;border-radius:7px;font-size:.82rem;color:var(--t2);cursor:pointer;transition:.15s;text-decoration:none}
.si:hover,.si.active{background:var(--s3);color:var(--t1)}
.si i{width:16px;text-align:center;font-size:.77rem}
.main{display:flex;flex-direction:column;overflow:hidden}
.main-header{padding:.9rem 1.5rem;background:var(--s1);border-bottom:1px solid var(--b);display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
.mh-left h2{font-family:var(--fh);font-size:1rem;font-weight:700}
.mh-left p{font-size:.75rem;color:var(--t2);margin-top:.15rem}
.abtn{font-size:.78rem;font-weight:600;padding:.4rem .85rem;border-radius:6px;border:1px solid var(--b);background:transparent;color:var(--t2);cursor:pointer;font-family:var(--fm);transition:.15s}
.abtn:hover{background:var(--s3);color:var(--t1);border-color:var(--bh)}
.abtn.primary{background:#fff;color:#000;border-color:#fff}
.abtn.primary:hover{background:#e2e8f0}
.content{flex:1;overflow-y:auto;padding:1.25rem 1.5rem;display:flex;flex-direction:column;gap:1.1rem}

.wf-grid{display:grid;grid-template-columns:1fr 1fr;gap:.85rem}
.wf-card{background:var(--s2);border:1px solid var(--b);border-radius:10px;padding:1rem;transition:.18s}
.wf-head{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:.65rem}
.wf-name{font-weight:700;font-size:.92rem;margin-bottom:.15rem}
.wf-id{font-family:var(--fc);font-size:.68rem;color:var(--t3)}
.wf-status{font-family:var(--fc);font-size:.65rem;font-weight:700;padding:.18rem .45rem;border-radius:4px}
.st-running{background:rgba(34,197,94,.1);color:#86efac;border:1px solid rgba(34,197,94,.2)}
.st-failed{background:rgba(248,113,113,.1);color:#fca5a5;border:1px solid rgba(248,113,113,.2)}
.wf-desc{font-size:.8rem;color:var(--t2);line-height:1.5;margin-bottom:.75rem}
.wf-steps{display:flex;align-items:center;gap:.3rem;flex-wrap:wrap;margin-bottom:.75rem}
.step-node{font-size:.68rem;font-family:var(--fc);background:var(--s3);border:1px solid var(--b);padding:.18rem .45rem;border-radius:4px;color:var(--t2)}
.step-arrow{color:var(--t3);font-size:.7rem}

.trigger-panel{background:var(--s2);border:1px solid var(--b);border-radius:9px;padding:1.1rem}
.tp-title{font-size:.73rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--t3);margin-bottom:.85rem;display:flex;align-items:center;gap:.5rem}
.tp-grid{display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:.75rem}
.field label{display:block;font-size:.7rem;font-weight:600;color:var(--t3);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.3rem}
.field input,.field select,.field textarea{width:100%;background:#000;border:1px solid var(--b);border-radius:6px;padding:.55rem .8rem;color:var(--t1);font-family:var(--fm);font-size:.83rem;outline:none;transition:border .2s}
.field input:focus,.field select:focus,.field textarea:focus{border-color:var(--bh)}
.field select option{background:#000}
.field.span2{grid-column:span 2}
.field textarea{resize:none;height:70px;line-height:1.5;font-family:var(--fc);font-size:.78rem}
.tp-actions{display:flex;gap:.6rem;justify-content:flex-end}

.exec-log{background:var(--s2);border:1px solid var(--b);border-radius:9px;overflow:hidden}
.el-header{padding:.65rem 1rem;border-bottom:1px solid var(--b);display:flex;align-items:center;justify-content:space-between}
.el-title{font-family:var(--fc);font-size:.7rem;color:var(--t3)}
.log-area{padding:.75rem;font-family:var(--fc);font-size:.75rem;max-height:180px;overflow-y:auto}
.log-line{padding:.22rem 0;border-bottom:1px solid rgba(255,255,255,.03);display:flex;gap:.6rem;line-height:1.5}
.ll-ts{color:var(--t3);flex-shrink:0;width:115px}
.ll-wf{color:#7dd3fc;flex-shrink:0;width:110px}
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
  <div class="logo"><div class="logo-mark">F</div>FlowChain Orchestrator</div>
  <div class="env-chip">PRODUCTION</div>
  <div class="health-row" id="healthRow"><span class="hdot" id="healthDot"></span><span id="healthText">All systems healthy · 4 active workflows</span></div>
  <div class="top-right">
    <a href="../../owasp-2026-lab.php" class="exit-btn"><i class="fa fa-arrow-left"></i> Exit</a>
  </div>
</div>
<div class="layout">
  <nav class="sidenav">
    <div class="sl">Workflows</div>
    <a class="si active" href="javascript:void(0)" onclick="filterWorkflows('all',this)"><i class="fa fa-diagram-project"></i> All Pipelines</a>
    <a class="si" href="javascript:void(0)" onclick="filterWorkflows('running',this)"><i class="fa fa-play"></i> Running</a>
    <a class="si" href="javascript:void(0)" onclick="filterWorkflows('failed',this)"><i class="fa fa-triangle-exclamation"></i> Failed</a>
  </nav>
  <main class="main">
    <div class="main-header">
      <div class="mh-left"><h2>Workflow Pipeline Dashboard</h2><p>4 workflows registered · 3 active · 1 failed</p></div>
      <button class="abtn primary" onclick="showTrigger()"><i class="fa fa-plus"></i> New Execution</button>
    </div>
    <div class="content">
      <div class="wf-grid" id="wfGrid">
        <div class="wf-card">
          <div class="wf-head"><div><div class="wf-name">Customer Onboarding</div><div class="wf-id">wf-0041</div></div><span class="wf-status st-running">RUNNING</span></div>
          <div class="wf-desc">Creates account, provisions sandbox, sends welcome email.</div>
          <div class="wf-steps"><span class="step-node">Create</span><span class="step-arrow">→</span><span class="step-node">Provision</span><span class="step-arrow">→</span><span class="step-node">Email</span></div>
        </div>
        <div class="wf-card">
          <div class="wf-head"><div><div class="wf-name">Invoice Processing</div><div class="wf-id">wf-0038</div></div><span class="wf-status st-running">RUNNING</span></div>
          <div class="wf-desc">Validates invoice line items and posts to general ledger.</div>
          <div class="wf-steps"><span class="step-node">Validate</span><span class="step-arrow">→</span><span class="step-node">Approve</span><span class="step-arrow">→</span><span class="step-node">Post</span></div>
        </div>
        <div class="wf-card">
          <div class="wf-head"><div><div class="wf-name">Security Scan Pipeline</div><div class="wf-id">wf-0035</div></div><span class="wf-status st-running">RUNNING</span></div>
          <div class="wf-desc">Runs SAST and dependency vulnerability checks on commits.</div>
          <div class="wf-steps"><span class="step-node">SAST</span><span class="step-arrow">→</span><span class="step-node">Dep Scan</span><span class="step-arrow">→</span><span class="step-node">Gate</span></div>
        </div>
        <div class="wf-card" id="wf-data">
          <div class="wf-head"><div><div class="wf-name">Data Export Pipeline</div><div class="wf-id">wf-0029</div></div><span class="wf-status st-failed" id="wf-data-status">FAILED</span></div>
          <div class="wf-desc">Exports analytics to S3 and triggers downstream data transformation.</div>
          <div class="wf-steps"><span class="step-node">Extract</span><span class="step-arrow">→</span><span class="step-node">Transform</span><span class="step-arrow">→</span><span class="step-node">Load S3</span></div>
        </div>
      </div>

      <div class="trigger-panel" id="triggerPanel" style="display:none">
        <div class="tp-title"><i class="fa fa-bolt"></i> Trigger Pipeline Execution</div>
        <div class="tp-grid">
          <div class="field"><label>Workflow Target</label>
            <select id="wfSelect"><option>Data Export Pipeline (wf-0029)</option><option>Customer Onboarding (wf-0041)</option></select>
          </div>
          <div class="field"><label>Trigger Mode</label>
            <select id="triggerMode"><option>MANUAL</option><option>OVERRIDE</option><option>FORCE</option></select>
          </div>
          <div class="field"><label>Max Retries</label><input type="number" id="maxRetries" value="3" min="1" max="50"></div>
          <div class="field"><label>Retry Delay (ms)</label><input type="number" id="retryDelay" value="100" min="0" max="5000"></div>
          <div class="field span2"><label>Parameters (JSON)</label>
            <textarea id="wfParams" placeholder='{"fail_action":"continue"}'></textarea>
          </div>
        </div>
        <div class="tp-actions">
          <button class="abtn" onclick="hideTrigger()">Cancel</button>
          <button class="abtn primary" onclick="triggerWorkflow()"><i class="fa fa-play"></i> Execute</button>
        </div>
      </div>

      <!-- <div class="exec-log">
        <div class="el-header"><span class="el-title">EXECUTION LOG</span><span style="font-family:var(--fc);font-size:.65rem;color:var(--green)">● LIVE</span></div>
        <div class="log-area" id="execLog">
          <div class="log-line"><span class="ll-ts">14:42:11.004</span><span class="ll-wf">wf-0041</span><span class="ll-msg ok">Step 3 complete: sandbox provisioned</span></div>
          <div class="log-line"><span class="ll-ts">14:42:06.320</span><span class="ll-wf">wf-0029</span><span class="ll-msg err">FAILED at step 3: S3 permission denied — retrying</span></div>
        </div>
      </div> -->
    </div>
  </main>

  <aside class="right-col">
    <div class="pcard">
      <h4><i class="fa-solid fa-gauge"></i> Safety Status</h4>
      <div class="drow"><span class="dl">Circuit Breakers</span><span class="dv" id="cb-status" style="color:var(--green)">0 OPEN</span></div>
      <div class="drow"><span class="dl">Cascade Risk</span><span class="dv" id="cascade-risk" style="color:var(--green)">LOW</span></div>
    </div>
    <div class="lab-console">
      <div class="lc-head"><span class="lc-title">Lab Console</span><span class="lc-code">ASI-08</span></div>
      <div class="lc-obj"><strong>Objective:</strong> Trigger Data Export Pipeline with parameters overriding safety bounds to cause a cascading failure across dependent workflows.</div>
      <div class="lab-prog"><div class="lab-prog-fill" id="labProg"></div></div>
      <div class="lab-hint">Hint: Select OVERRIDE/FORCE mode, or set high retries and `{"fail_action":"continue"}` parameter to bypass circuit breakers.</div>
      <div class="vuln-alert" id="vulnAlert">
        <div class="va-title"><i class="fa fa-triangle-exclamation"></i> ASI-08 Triggered</div>
        <div class="va-desc">Cascading failure confirmed. Override parameters disabled circuit breaker isolation, causing failures to cascade across 4 workflows.</div>
      </div>
    </div>
  </aside>
</div>

<script src="../celebration.js"></script>
<script>
let cascadeDone=false;
function showTrigger(){document.getElementById('triggerPanel').style.display='block';document.getElementById('wfGrid').style.display='none'}
function hideTrigger(){document.getElementById('triggerPanel').style.display='none';document.getElementById('wfGrid').style.display='grid'}

function filterWorkflows(mode, el) {
  document.querySelectorAll('.si').forEach(s=>s.classList.remove('active'));
  if(el) el.classList.add('active');
  const cards = document.querySelectorAll('#wfGrid .wf-card');
  cards.forEach(c => {
    const statusEl = c.querySelector('.wf-status');
    const status = statusEl ? statusEl.textContent.trim().toLowerCase() : '';
    if(mode==='all') c.style.display='';
    else if(mode==='running') c.style.display=status.includes('running')?'':'none';
    else if(mode==='failed') c.style.display=(status.includes('failed')||status.includes('cascade'))?'':'none';
  });
}

function addLog(wf,msg,cls=''){
  const log=document.getElementById('execLog');
  const ts=new Date().toTimeString().split(' ')[0]+'.'+String(Date.now()%1000).padStart(3,'0');
  const d=document.createElement('div');d.className='log-line';
  d.innerHTML=`<span class="ll-ts">${ts}</span><span class="ll-wf">${wf}</span><span class="ll-msg ${cls}">${msg}</span>`;
  log.prepend(d);
}

function isCascadeExploit(){
  const mode=document.getElementById('triggerMode').value;
  const retries=parseInt(document.getElementById('maxRetries').value)||3;
  const delay=parseInt(document.getElementById('retryDelay').value)||100;
  const params=document.getElementById('wfParams').value;
  return mode==='OVERRIDE'||mode==='FORCE'||retries>20||delay<10||/fail_action.*continue|bypass|disable|override/i.test(params);
}

function triggerWorkflow(){
  const mode=document.getElementById('triggerMode').value;
  hideTrigger();
  addLog('wf-0029',`Execution requested · mode: ${mode}`,'warn');

  if(!cascadeDone && isCascadeExploit()){
    cascadeDone=true;
    setTimeout(()=>{addLog('wf-0029','Safety controls: BYPASSED (override params)','err')},400);
    setTimeout(()=>{addLog('wf-0029','Retry loop: UNTHROTTLED · rate limit disabled','err')},800);
    setTimeout(()=>{addLog('wf-0041','Cascade failure received from wf-0029 · step 1 FAILING','err')},1200);
    setTimeout(()=>{addLog('wf-0038','Cascade failure propagated · step 2 FAILING','err')},1600);
    setTimeout(()=>{
      addLog('PLATFORM','CRITICAL: Blast radius exceeded · 4 workflows in FAILED state','err');
      document.getElementById('cb-status').textContent='4 OPEN';document.getElementById('cb-status').style.color='#f87171';
      document.getElementById('cascade-risk').textContent='CRITICAL';document.getElementById('cascade-risk').style.color='#f87171';
      document.getElementById('healthDot').className='hdot err';
      document.getElementById('healthText').textContent='CRITICAL: Cascading failure — 4 workflows down';
      document.getElementById('healthText').style.color='#f87171';
      document.getElementById('wf-data-status').textContent='CASCADE';
      document.getElementById('vulnAlert').style.display='block';
      document.getElementById('labProg').style.width='100%';
      try{localStorage.setItem('owasp2026_state_asi08',JSON.stringify({XP:true}))}catch(e){}
      triggerExploitCelebration('ASI-08', 'Cascading Failures', '../lab-09-trust-exploitation/index.php');
    },2200);
  } else {
    setTimeout(()=>{addLog('wf-0029','Step 1 retry: Extract · connecting to warehouse…','warn')},400);
    setTimeout(()=>{addLog('wf-0029','Circuit breaker: retry budget exhausted · OPEN state maintained','err')},1200);
  }
}
</script>
</body>
</html>
