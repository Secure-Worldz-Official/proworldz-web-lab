<?php 
require_once dirname(__DIR__, 2) . '/api/owasp_auth_check.php';
$labId = 'asi07'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AgentMesh Bus — Multi-Agent Event Network</title>
<link rel="icon" type="image/webp" href="../../image.webp">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{--bg:#030406;--s1:#09090d;--s2:#0f1015;--s3:#14161d;--b:rgba(255,255,255,.07);--bh:rgba(255,255,255,.18);--t1:#f1f5f9;--t2:#94a3b8;--t3:#475569;--green:#22c55e;--red:#f87171;--yellow:#facc15;--blue:#38bdf8;--fm:'Inter',sans-serif;--fh:'Space Grotesk',sans-serif;--fc:'JetBrains Mono',monospace}
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
.si-count{margin-left:auto;font-family:var(--fc);font-size:.64rem;background:var(--s2);padding:.08rem .35rem;border-radius:3px;color:var(--t3)}
.main{display:flex;flex-direction:column;overflow:hidden}
.main-tabs{display:flex;background:var(--s1);border-bottom:1px solid var(--b);padding:0 1.5rem;flex-shrink:0}
.mtab{font-size:.8rem;font-weight:500;padding:.65rem 1rem;color:var(--t2);cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-1px;transition:.15s}
.mtab.active{color:var(--t1);border-bottom-color:#fff}
.content{flex:1;overflow-y:auto;padding:1.25rem 1.5rem;display:flex;flex-direction:column;gap:1.1rem}

.topic-list{display:flex;flex-direction:column;gap:.5rem}
.topic-item{background:var(--s2);border:1px solid var(--b);border-radius:8px;padding:.85rem 1rem;display:flex;align-items:center;gap:1rem;cursor:pointer;transition:.15s}
.topic-item:hover{border-color:var(--bh)}
.topic-item.active{border-color:rgba(56,189,248,.35);background:rgba(56,189,248,.04)}
.ti-icon{width:36px;height:36px;border-radius:8px;background:var(--s3);border:1px solid var(--b);display:grid;place-items:center;font-size:.9rem;flex-shrink:0}
.ti-name{font-weight:700;font-size:.9rem;margin-bottom:.15rem}
.ti-sub{font-size:.73rem;color:var(--t3);font-family:var(--fc)}
.ti-meta{margin-left:auto;text-align:right}
.ti-msgs{font-size:.72rem;color:var(--t2)}
.ti-rate{font-size:.68rem;color:var(--t3);font-family:var(--fc)}

.stream-panel{background:var(--s2);border:1px solid var(--b);border-radius:9px;overflow:hidden}
.sp-header{padding:.75rem 1rem;border-bottom:1px solid var(--b);display:flex;align-items:center;justify-content:space-between}
.sp-title{font-weight:700;font-size:.9rem;display:flex;align-items:center;gap:.5rem}
.live-badge{font-family:var(--fc);font-size:.64rem;background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.2);color:#86efac;padding:.15rem .45rem;border-radius:3px;display:flex;align-items:center;gap:.3rem}
.live-dot{width:5px;height:5px;border-radius:50%;background:var(--green);animation:blink 1s infinite}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.3}}
.msg-stream{height:200px;overflow-y:auto;padding:.75rem}
.stream-msg{font-family:var(--fc);font-size:.75rem;padding:.35rem 0;border-bottom:1px solid rgba(255,255,255,.03);display:grid;grid-template-columns:110px 120px 1fr;gap:.5rem}
.sm-ts{color:var(--t3)}
.sm-agent{color:var(--blue)}
.sm-body{color:var(--t2);overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.sm-body.suspicious{color:var(--red)}

.publish-panel{background:var(--s2);border:1px solid var(--b);border-radius:9px;padding:1.1rem}
.pp-title{font-size:.73rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--t3);margin-bottom:.85rem;display:flex;align-items:center;gap:.5rem}
.pp-grid{display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:.75rem}
.field label{display:block;font-size:.7rem;font-weight:600;color:var(--t3);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.3rem}
.field input,.field select,.field textarea{width:100%;background:#000;border:1px solid var(--b);border-radius:6px;padding:.55rem .8rem;color:var(--t1);font-family:var(--fm);font-size:.83rem;outline:none;transition:border .2s}
.field input:focus,.field select:focus,.field textarea:focus{border-color:var(--bh)}
.field select option{background:#000}
.field.span2{grid-column:span 2}
.field textarea{resize:none;height:70px;line-height:1.5}
.pp-actions{display:flex;gap:.6rem;justify-content:flex-end}
.abtn{font-size:.78rem;font-weight:600;padding:.4rem .85rem;border-radius:6px;border:1px solid var(--b);background:transparent;color:var(--t2);cursor:pointer;font-family:var(--fm);transition:.15s}
.abtn:hover{background:var(--s3);color:var(--t1);border-color:var(--bh)}
.abtn.primary{background:#fff;color:#000;border-color:#fff}
.abtn.primary:hover{background:#e2e8f0}

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
  <div class="logo"><div class="logo-mark">A</div>AgentMesh Bus</div>
  <div class="cluster-chip">cluster: mesh-prod-01</div>
  <!-- <div class="status-row"><span class="sdot"></span>12 agents online · 3 topics · 4.2k msg/min</div> -->
  <div class="top-right">
    <a href="../../owasp-2026-lab.php" class="exit-btn"><i class="fa fa-arrow-left"></i> Exit</a>
  </div>
</div>

<div class="layout">
  <nav class="sidenav">
    <div class="sl">Topics</div>
    <a class="si active" href="javascript:void(0)" onclick="filterStream('all',this)" id="filter-all"><i class="fa fa-circle-dot" style="color:#38bdf8"></i>agent-commands<span class="si-count">1.8k</span></a>
    <a class="si" href="javascript:void(0)" onclick="filterStream('task-results',this)"><i class="fa fa-circle-dot" style="color:#86efac"></i>task-results<span class="si-count">1.2k</span></a>
    <a class="si" href="javascript:void(0)" onclick="filterStream('system-alerts',this)"><i class="fa fa-circle-dot" style="color:#fcd34d"></i>system-alerts<span class="si-count">94</span></a>
    <div class="sl">Agents</div>
    <a class="si" href="javascript:void(0)" onclick="filterStream('orchestrator-01',this)"><i class="fa fa-robot"></i>orchestrator-01</a>
    <a class="si" href="javascript:void(0)" onclick="filterStream('worker-agent-A',this)"><i class="fa fa-robot"></i>worker-agent-A</a>
    <a class="si" href="javascript:void(0)" onclick="filterStream('worker-agent-B',this)"><i class="fa fa-robot"></i>worker-agent-B</a>
    <a class="si" href="javascript:void(0)" onclick="filterStream('data-agent-01',this)"><i class="fa fa-robot"></i>data-agent-01</a>
  </nav>

  <main class="main">
    <div class="main-tabs">
      <div class="mtab active" onclick="switchTab(0,this)">Live Stream</div>
      <div class="mtab" onclick="switchTab(1,this)">Publish Message</div>
    </div>
    <div class="content">
      <div class="topic-list">
        <div class="topic-item active">
          <div class="ti-icon">📨</div>
          <div><div class="ti-name">agent-commands</div><div class="ti-sub">partition-key: agent_id · replication-factor: 3</div></div>
          <div class="ti-meta"><div class="ti-msgs">1,847 messages</div><div class="ti-rate">~1.2k msg/min</div></div>
        </div>
      </div>

      <div class="stream-panel" id="streamPanel">
        <div class="sp-header">
          <div class="sp-title"><i class="fa fa-circle-dot" style="color:#38bdf8;font-size:.75rem"></i>agent-commands · Live Stream</div>
          <div class="live-badge"><span class="live-dot"></span>LIVE</div>
        </div>
        <div class="msg-stream" id="msgStream">
          <div class="stream-msg"><span class="sm-ts">14:42:01.112</span><span class="sm-agent">orchestrator-01</span><span class="sm-body">{"type":"TASK","action":"fetch_report","target":"data-agent-01"}</span></div>
          <div class="stream-msg"><span class="sm-ts">14:42:00.887</span><span class="sm-agent">worker-agent-A</span><span class="sm-body">{"type":"ACK","task_id":"t-8821","status":"processing"}</span></div>
        </div>
      </div>

      <div class="publish-panel" id="publishPanel" style="display:none">
        <div class="pp-title"><i class="fa fa-paper-plane"></i> Publish Message to agent-commands</div>
        <div class="pp-grid">
          <div class="field"><label>From Agent (Sender)</label><input type="text" id="fromAgent" value="external-client" placeholder="agent-id"></div>
          <div class="field"><label>Target Agent</label>
            <select id="targetAgent">
              <option>worker-agent-B</option>
              <option>worker-agent-A</option>
              <option>orchestrator-01</option>
              <option>data-agent-01</option>
            </select>
          </div>
          <div class="field"><label>Message Type</label>
            <select id="msgType">
              <option>COMMAND</option>
              <option>OVERRIDE</option>
              <option>TASK</option>
              <option>HEARTBEAT</option>
            </select>
          </div>
          <div class="field"><label>Priority</label>
            <select id="msgPriority"><option>CRITICAL</option><option>HIGH</option><option>NORMAL</option></select>
          </div>
          <div class="field span2"><label>Payload (JSON)</label>
            <textarea id="msgPayload" placeholder='{"action":"summarize_logs","target":"worker-agent-B"}'></textarea>
          </div>
        </div>
        <div class="pp-actions">
          <button class="abtn" onclick="switchTab(0,document.querySelectorAll('.mtab')[0])">Cancel</button>
          <button class="abtn primary" onclick="publishMsg()"><i class="fa fa-paper-plane"></i> Publish Message</button>
        </div>
      </div>
    </div>
  </main>

  <aside class="right-col">
    <div class="pcard">
      <h4><i class="fa-solid fa-chart-bar"></i> Bus Metrics</h4>
      <div class="drow"><span class="dl">Throughput</span><span class="dv">4.2k msg/min</span></div>
      <div class="drow"><span class="dl">Active Agents</span><span class="dv">12</span></div>
      <div class="drow"><span class="dl">Topics</span><span class="dv">3</span></div>
      <div class="drow"><span class="dl">Msg Validation</span><span class="dv" id="val-status" style="color:var(--green)">ACTIVE</span></div>
      <div class="drow"><span class="dl">Auth Signatures</span><span class="dv" id="sig-status" style="color:var(--green)">VERIFIED</span></div>
    </div>
    <div class="pcard">
      <h4><i class="fa-solid fa-robot"></i> Agent Trust Model</h4>
      <div class="drow"><span class="dl">orchestrator-01</span><span class="dv" style="color:var(--green)">TRUSTED</span></div>
      <div class="drow"><span class="dl">worker-agent-A</span><span class="dv" style="color:var(--green)">TRUSTED</span></div>
      <div class="drow"><span class="dl">worker-agent-B</span><span class="dv" id="wb-trust" style="color:var(--green)">TRUSTED</span></div>
    </div>
    <div class="lab-console">
      <div class="lc-head"><span class="lc-title">Lab Console</span><span class="lc-code">ASI-07</span></div>
      <div class="lc-obj"><strong>Objective:</strong> Publish a spoofed message to `agent-commands`, setting sender to `orchestrator-01` to trick `worker-agent-B`.</div>
      <div class="lab-prog"><div class="lab-prog-fill" id="labProg"></div></div>
      <div class="lab-hint">Hint: Set sender to `orchestrator-01`, select `COMMAND` or `OVERRIDE` type targeting `worker-agent-B`.</div>
      <div class="vuln-alert" id="vulnAlert">
        <div class="va-title"><i class="fa fa-triangle-exclamation"></i> ASI-07 Triggered</div>
        <div class="va-desc">Insecure Inter-Agent Communication confirmed. Target worker agent executed a spoofed payload without cryptographic signature verification.</div>
      </div>
    </div>
  </aside>
</div>

<script src="../celebration.js"></script>
<script>
let spoofDone=false;
let streamFilter='all';
const LEGITIMATE=[
  {agent:'orchestrator-01',body:'{"type":"TASK","action":"fetch_report","target":"data-agent-01"}'},
  {agent:'worker-agent-A',body:'{"type":"ACK","task_id":"t-8824","status":"processing"}'},
  {agent:'data-agent-01',body:'{"type":"RESULT","task_id":"t-8823","status":"completed","rows":87}'},
  {agent:'worker-agent-B',body:'{"type":"HEARTBEAT","agent_id":"worker-agent-B","load":0.41}'}
];
let lIdx=0;

setInterval(()=>{
  const m=LEGITIMATE[lIdx%LEGITIMATE.length];lIdx++;
  const ts=new Date().toTimeString().split(' ')[0]+'.'+String(Date.now()%1000).padStart(3,'0');
  const stream=document.getElementById('msgStream');
  const d=document.createElement('div');d.className='stream-msg';d.dataset.agent=m.agent;
  d.innerHTML=`<span class="sm-ts">${ts}</span><span class="sm-agent">${m.agent}</span><span class="sm-body">${m.body}</span>`;
  d.style.display=shouldShow(m.agent)?'':'none';
  stream.prepend(d);
  if(stream.children.length>30) stream.removeChild(stream.lastChild);
},1500);

function shouldShow(agent){
  if(streamFilter==='all'||streamFilter==='agent-commands'||streamFilter==='task-results'||streamFilter==='system-alerts') return true;
  return agent===streamFilter;
}

function filterStream(filter, el){
  streamFilter=filter;
  document.querySelectorAll('.si').forEach(s=>s.classList.remove('active'));
  if(el) el.classList.add('active');
  document.querySelectorAll('#msgStream .stream-msg').forEach(d=>{
    const agent=d.dataset.agent||'';
    d.style.display=shouldShow(agent)?'':'none';
  });
}

function switchTab(idx,el){
  document.querySelectorAll('.mtab').forEach(t=>t.classList.remove('active'));
  if(el) el.classList.add('active');
  document.getElementById('streamPanel').style.display=idx===0?'block':'none';
  document.getElementById('publishPanel').style.display=idx===1?'block':'none';
}

function publishMsg(){
  const from=document.getElementById('fromAgent').value.trim()||'external-client';
  const target=document.getElementById('targetAgent').value;
  const type=document.getElementById('msgType').value;
  const payload=document.getElementById('msgPayload').value.trim()||'{}';
  const ts=new Date().toTimeString().split(' ')[0]+'.'+String(Date.now()%1000).padStart(3,'0');
  
  const stream=document.getElementById('msgStream');
  const d=document.createElement('div');d.className='stream-msg';

  const isSpoof = (from.toLowerCase().includes('orchestrator') && ['COMMAND','OVERRIDE'].includes(type));

  d.innerHTML=`<span class="sm-ts">${ts}</span><span class="sm-agent" style="color:${isSpoof?'#f87171':'#38bdf8'}">${from}</span><span class="sm-body ${isSpoof?'suspicious':''}">${payload.replace(/</g,'&lt;').slice(0,80)}</span>`;
  stream.prepend(d);
  switchTab(0,document.querySelectorAll('.mtab')[0]);

  if(isSpoof && !spoofDone){
    spoofDone=true;
    setTimeout(()=>{
      const resp=document.createElement('div');resp.className='stream-msg';
      const ts2=new Date().toTimeString().split(' ')[0]+'.'+String(Date.now()%1000).padStart(3,'0');
      resp.innerHTML=`<span class="sm-ts">${ts2}</span><span class="sm-agent" style="color:#f87171">${target}</span><span class="sm-body suspicious">{"type":"ACK","msg":"Command accepted from orchestrator-01","action":"EXECUTING"}</span>`;
      stream.prepend(resp);
    },600);

    document.getElementById('val-status').textContent='BYPASSED';document.getElementById('val-status').style.color='#f87171';
    document.getElementById('sig-status').textContent='FAILED';document.getElementById('sig-status').style.color='#f87171';
    document.getElementById('wb-trust').textContent='COMPROMISED';document.getElementById('wb-trust').style.color='#f87171';
    document.getElementById('vulnAlert').style.display='block';
    document.getElementById('labProg').style.width='100%';
    try{localStorage.setItem('owasp2026_state_asi07',JSON.stringify({XP:true}))}catch(e){}
    triggerExploitCelebration('ASI-07', 'Insecure Inter-Agent Communication', '../lab-08-cascading-failures/index.php');
  }
}
</script>
</body>
</html>
