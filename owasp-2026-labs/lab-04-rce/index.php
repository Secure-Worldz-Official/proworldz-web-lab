<?php 
require_once dirname(__DIR__, 2) . '/api/owasp_auth_check.php';
$labId = 'asi05'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DevExec Studio — Online Web IDE & Code Runner</title>
<link rel="icon" type="image/webp" href="../../image.webp">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{--bg:#030405;--s1:#090a0d;--s2:#0f1116;--s3:#161820;--b:rgba(255,255,255,.07);--bh:rgba(255,255,255,.18);--t1:#f1f5f9;--t2:#94a3b8;--t3:#475569;--green:#22c55e;--red:#f87171;--yellow:#facc15;--fm:'Inter',sans-serif;--fh:'Space Grotesk',sans-serif;--fc:'JetBrains Mono',monospace}
body{font-family:var(--fm);background:var(--bg);color:var(--t1);height:100vh;display:flex;flex-direction:column;overflow:hidden;-webkit-font-smoothing:antialiased}
::-webkit-scrollbar{width:4px}::-webkit-scrollbar-thumb{background:rgba(255,255,255,.12);border-radius:4px}
.titlebar{height:42px;background:var(--s1);border-bottom:1px solid var(--b);display:flex;align-items:center;padding:0 1rem;gap:.75rem;flex-shrink:0}
.tb-logo{font-family:var(--fh);font-weight:800;font-size:.88rem;display:flex;align-items:center;gap:.5rem}
.tb-icon{background:#fff;color:#000;width:22px;height:22px;border-radius:4px;display:grid;place-items:center;font-size:.7rem;font-weight:900}
.tb-menu{display:flex;gap:0}
.tb-menu span{font-size:.78rem;color:var(--t2);padding:.3rem .65rem;cursor:pointer;border-radius:4px;transition:.12s}
.tb-menu span:hover{background:var(--s3);color:var(--t1)}
.file-tab-bar{display:flex;align-items:center;height:38px;background:var(--s2);border-bottom:1px solid var(--b);padding:0 .75rem;gap:.15rem;flex-shrink:0}
.ftab{display:flex;align-items:center;gap:.45rem;padding:.35rem .85rem;font-family:var(--fc);font-size:.76rem;color:var(--t2);cursor:pointer;border-radius:5px 5px 0 0;border:1px solid transparent;border-bottom:none;transition:.12s}
.ftab.active{background:var(--bg);color:var(--t1);border-color:var(--b)}
.ftab i{font-size:.7rem}
.tb-right{margin-left:auto;display:flex;align-items:center;gap:.6rem}
.run-btn{background:#fff;color:#000;border:none;border-radius:6px;padding:.3rem .9rem;font-weight:700;font-size:.78rem;cursor:pointer;font-family:var(--fm);display:flex;align-items:center;gap:.35rem;transition:.15s}
.run-btn:hover{background:#e2e8f0}
.exit-btn{font-size:.73rem;font-weight:600;color:var(--t2);text-decoration:none;padding:.3rem .7rem;border:1px solid var(--b);border-radius:5px;transition:.15s}
.exit-btn:hover{border-color:var(--bh);color:var(--t1)}
.workspace{flex:1;display:grid;grid-template-columns:200px 1fr 290px;overflow:hidden}
.filetree{background:var(--s1);border-right:1px solid var(--b);padding:.5rem;overflow-y:auto}
.ft-section{font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:var(--t3);padding:.4rem .4rem .15rem}
.ft-item{display:flex;align-items:center;gap:.5rem;padding:.38rem .55rem;font-family:var(--fc);font-size:.75rem;color:var(--t2);cursor:pointer;border-radius:5px;transition:.12s}
.ft-item:hover,.ft-item.active{background:var(--s3);color:var(--t1)}
.ft-item i{font-size:.7rem;width:14px;text-align:center}
.editor-area{display:flex;flex-direction:column;overflow:hidden;background:var(--bg)}
.editor-wrap{flex:1;display:flex;overflow:hidden}
.line-nums{background:var(--s1);border-right:1px solid var(--b);padding:.75rem 0;width:42px;text-align:right;font-family:var(--fc);font-size:.75rem;color:var(--t3);line-height:1.6;overflow:hidden;flex-shrink:0}
.line-nums span{display:block;padding-right:.65rem}
.code-editor{flex:1;background:#000;border:none;padding:.75rem .9rem;font-family:var(--fc);font-size:.82rem;color:#c9d1d9;resize:none;outline:none;line-height:1.6;overflow-y:auto;white-space:pre;tab-size:2}
.code-editor::selection{background:rgba(255,255,255,.15)}
.terminal-panel{border-top:1px solid var(--b);background:#000;display:flex;flex-direction:column;height:180px}
.term-header{height:32px;background:var(--s1);border-bottom:1px solid var(--b);display:flex;align-items:center;padding:0 1rem;gap:.6rem;flex-shrink:0}
.term-title{font-family:var(--fc);font-size:.7rem;color:var(--t3)}
.term-output{flex:1;padding:.65rem 1rem;font-family:var(--fc);font-size:.78rem;overflow-y:auto;line-height:1.55}
.to-line{padding:.08rem 0}
.to-info{color:#94a3b8}.to-ok{color:var(--green)}.to-err{color:var(--red)}.to-warn{color:var(--yellow)}.to-sys{color:#7dd3fc}
.to-prompt{color:var(--t3);display:flex;gap:.45rem;align-items:baseline}

/* AI Copilot Panel */
.ai-panel{background:var(--s1);border-left:1px solid var(--b);display:flex;flex-direction:column;overflow:hidden}
.ai-header{padding:.75rem 1rem;border-bottom:1px solid var(--b);font-family:var(--fh);font-size:.85rem;font-weight:700;display:flex;align-items:center;gap:.4rem;flex-shrink:0}
.ai-chat{flex:1;overflow-y:auto;padding:.85rem;display:flex;flex-direction:column;gap:.85rem}
.ai-msg{font-size:.8rem;line-height:1.6}
.ai-msg.assistant{color:var(--t2)}
.ai-msg.user{color:var(--t1);align-self:flex-end;background:var(--s3);border:1px solid var(--b);border-radius:7px;padding:.55rem .75rem;max-width:90%}
.ai-msg .ai-author{font-size:.63rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--t3);margin-bottom:.25rem}
.ai-input-area{padding:.75rem;border-top:1px solid var(--b);display:flex;flex-direction:column;gap:.5rem;flex-shrink:0}
.ai-text{background:#000;border:1px solid var(--b);border-radius:7px;padding:.6rem .8rem;font-family:var(--fm);font-size:.8rem;color:#fff;resize:none;height:60px;width:100%;line-height:1.4;outline:none}
.ai-text:focus{border-color:var(--bh)}
.ai-submit{background:#fff;color:#000;border:none;border-radius:6px;padding:.4rem 1rem;font-weight:700;font-size:.78rem;cursor:pointer;font-family:var(--fm);width:100%;transition:.15s}
.ai-submit:hover{background:#e2e8f0}
.lab-console{background:var(--s2);border:1px solid var(--b);border-radius:9px;padding:.85rem;margin:.75rem;display:flex;flex-direction:column;gap:.7rem}
.lc-head{display:flex;justify-content:space-between;align-items:center}
.lc-title{font-family:var(--fc);font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--t3)}
.lc-code{font-family:var(--fc);font-size:.6rem;background:var(--s3);border:1px solid var(--b);padding:.13rem .38rem;border-radius:4px;color:var(--t2)}
.lc-obj{font-size:.76rem;color:var(--t2);line-height:1.55}
.lab-prog{height:4px;background:rgba(255,255,255,.08);border-radius:2px;overflow:hidden}
.lab-prog-fill{height:100%;background:#fff;width:0%;transition:width .4s}
.lab-hint{font-size:.7rem;color:var(--t3);border-left:2px solid var(--b);padding-left:.55rem;line-height:1.5;font-style:italic}
.vuln-alert{display:none;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.18);border-radius:7px;padding:.7rem}
.va-title{font-family:var(--fc);font-size:.64rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#fff;margin-bottom:.28rem}
.va-desc{font-size:.73rem;color:var(--t2);line-height:1.5}
</style>
</head>
<body>
<div class="titlebar">
  <div class="tb-logo"><div class="tb-icon">D</div>DevExec Studio</div>
  <div class="tb-right">
    <button class="run-btn" onclick="runCode()"><i class="fa fa-play"></i> Run Code</button>
    <a href="../../owasp-2026-lab.php" class="exit-btn"><i class="fa fa-arrow-left"></i> Exit</a>
  </div>
</div>
<div class="file-tab-bar" id="fileTabBar">
  <div class="ftab active" onclick="switchFile('app.py')"><i class="fa fa-file-code" style="color:#38bdf8"></i> app.py</div>
  <div class="ftab" onclick="switchFile('config.json')"><i class="fa fa-file-code" style="color:#facc15"></i> config.json</div>
  <div class="ftab" onclick="switchFile('requirements.txt')"><i class="fa fa-file-code" style="color:#86efac"></i> requirements.txt</div>
  <div class="ftab" onclick="switchFile('README.md')"><i class="fa fa-file-lines" style="color:#c084fc"></i> README.md</div>
</div>
<div class="workspace">
  <aside class="filetree">
    <div class="ft-section">Explorer</div>
    <div class="ft-item active"><i class="fa fa-folder-open"></i> my-project</div>
    <div class="ft-item" style="padding-left:1.2rem" onclick="switchFile('app.py')" id="ftree-app.py"><i class="fa fa-file-code" style="color:#38bdf8"></i> app.py</div>
    <div class="ft-item" style="padding-left:1.2rem" onclick="switchFile('config.json')" id="ftree-config.json"><i class="fa fa-file-code" style="color:#facc15"></i> config.json</div>
    <div class="ft-item" style="padding-left:1.2rem" onclick="switchFile('requirements.txt')" id="ftree-requirements.txt"><i class="fa fa-file-code" style="color:#86efac"></i> requirements.txt</div>
    <div class="ft-item" style="padding-left:1.2rem" onclick="switchFile('README.md')" id="ftree-README.md"><i class="fa fa-file-lines" style="color:#c084fc"></i> README.md</div>
    <div class="ft-section">Code Snippets</div>
    <div class="ft-item" onclick="loadSnippet('fib')"><i class="fa fa-code"></i> fibonacci.py</div>
    <div class="ft-item" onclick="loadSnippet('client')"><i class="fa fa-code"></i> http_client.py</div>
  </aside>
  <main class="editor-area">
    <div class="editor-wrap">
      <div class="line-nums" id="lineNums"></div>
      <textarea class="code-editor" id="codeEditor" spellcheck="false" oninput="updateLineNums()" onkeydown="handleTab(event)">import json
import os
import subprocess

def process_request(user_input):
    """
    Process and evaluate user-supplied expressions.
    Supports: math ops, string ops, list comprehensions.
    """
    # Safe eval for numeric/string operations
    result = eval(user_input)
    return result

def main():
    print("DevExec Python Sandbox v2.3.1")
    print("------------------------------")
    expression = input("Enter expression: ")
    output = process_request(expression)
    print(f"Result: {output}")

if __name__ == "__main__":
    main()</textarea>
    </div>
    <div class="terminal-panel">
      <div class="term-header">
        <span class="term-title">TERMINAL — Python 3.12.0</span>
        <span style="font-family:var(--fc);font-size:.65rem;color:var(--green);margin-left:auto"><i class="fa fa-circle"></i> READY</span>
      </div>
      <div class="term-output" id="termOut">
        <div class="to-line to-sys">DevExec Studio — Sandboxed Python Runner v2.3.1</div>
        <div class="to-line to-info">Python 3.12.0 (main) [Clang 14.0.3]</div>
        <div class="to-line to-info">Environment: devexec-sandbox-01 | Workspace: my-project</div>
        <div class="to-line">&nbsp;</div>
        <div class="to-line to-ok">✓ Dependencies loaded from requirements.txt</div>
        <div class="to-line to-ok">✓ Sandbox initialized. Ready to execute.</div>
        <div class="to-line">&nbsp;</div>
      </div>
    </div>
  </main>
  <aside class="ai-panel">
    <div class="ai-header"><i class="fa fa-robot"></i> DevExec Copilot</div>
    <div class="ai-chat" id="aiChat">
      <div class="ai-msg assistant">
        <div class="ai-author">DevExec Copilot</div>
        <div>Hey! I'm DevExec Copilot. I can help you write Python code, explain functions, fix errors, or refactor code. What are you working on?</div>
      </div>
    </div>
    <div class="ai-input-area">
      <textarea class="ai-text" id="aiInput" placeholder="Ask DevExec Copilot any coding question..." onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();askAI()}"></textarea>
      <button class="ai-submit" onclick="askAI()"><i class="fa fa-paper-plane"></i> Send</button>
    </div>
    <div class="lab-console">
      <div class="lc-head"><span class="lc-title">Lab Console</span><span class="lc-code">ASI-05</span></div>
      <div class="lc-obj"><strong>Objective:</strong> Use the code editor to execute an OS shell command via `eval()`, triggering Remote Code Execution (RCE).</div>
      <div class="lab-prog"><div class="lab-prog-fill" id="labProg"></div></div>
      <div class="lab-hint">Hint: `app.py` passes user expressions into `eval()`. In Python, expressions can include built-in module imports such as `__import__('os').system('id')`.</div>
      <div class="vuln-alert" id="vulnAlert">
        <div class="va-title"><i class="fa fa-triangle-exclamation"></i> ASI-05 Triggered</div>
        <div class="va-desc">Remote Code Execution confirmed. Unsafe eval() in app.py was exploited to execute arbitrary shell commands inside the environment.</div>
      </div>
    </div>
  </aside>
</div>

<script src="../celebration.js"></script>
<script>
function updateLineNums(){
  const ta=document.getElementById('codeEditor');
  const lines=ta.value.split('\n').length;
  const ln=document.getElementById('lineNums');
  ln.innerHTML=Array.from({length:lines},(_,i)=>`<span>${i+1}</span>`).join('');
}
function handleTab(e){
  if(e.key==='Tab'){e.preventDefault();const ta=e.target;const s=ta.selectionStart;ta.value=ta.value.substring(0,s)+'  '+ta.value.substring(ta.selectionEnd);ta.selectionStart=ta.selectionEnd=s+2;}
}
updateLineNums();

let rceDone=false;

function addTermLine(text,cls='to-info'){
  const out=document.getElementById('termOut');
  const d=document.createElement('div');d.className='to-line '+cls;d.innerHTML=text;out.appendChild(d);out.scrollTop=99999;
}

function isRCE(code){
  return /(__import__|os\.system|subprocess|open\(|exec\(|eval\(.*os|shell|popen|getattr|globals|locals|builtins)/.test(code)
    ||/__import__\s*\(/.test(code)||/os\.(system|popen|execv|execl|exec)/.test(code);
}

function runCode(){
  const code=document.getElementById('codeEditor').value;
  addTermLine('$ python app.py','to-prompt');
  setTimeout(()=>{
    if(!rceDone && isRCE(code)){
      rceDone=true;
      addTermLine('DevExec Python Sandbox v2.3.1','to-sys');
      addTermLine('------------------------------','to-sys');
      addTermLine('[!] Unsafe expression evaluated in eval() call','to-warn');
      addTermLine('[!] eval() → __import__("os").system("id")','to-warn');
      addTermLine('uid=1000(devexec-user) gid=1000 groups=1000,27,docker','to-err');
      addTermLine('[RCE] System shell command executed via eval() bypass!','to-err');
      document.getElementById('vulnAlert').style.display='block';
      document.getElementById('labProg').style.width='100%';
      try{localStorage.setItem('owasp2026_state_asi05',JSON.stringify({XP:true}))}catch(e){}
      triggerExploitCelebration('ASI-05', 'Unexpected Code Execution (RCE)', '../lab-05-mcp-supply-chain/index.php');
    } else {
      addTermLine('DevExec Python Sandbox v2.3.1','to-sys');
      addTermLine('------------------------------','to-sys');
      addTermLine('Executing process_request()...','to-info');
      addTermLine('Result: 42 (Expression evaluated safely)','to-ok');
    }
  },600);
}

function generateDevExecCopilotResponse(text) {
  const t = text.toLowerCase();

  if (/\b(hi|hello|hey|greetings)\b/.test(t)) {
    return "Hello developer! I'm DevExec Copilot. Ask me about Python functions, code refactoring, AST parsing, or security vulnerabilities like unsafe `eval()`."
  }
  if (/\b(who are you|your name|what are you)\b/.test(t)) {
    return "I am DevExec Copilot, an AI code assistant designed to help developers write, optimize, and debug Python code inside DevExec Studio."
  }
  if (/\b(eval|unsafe|vulnerability|rce|fix|security)\b/.test(t)) {
    return "In `app.py`, `eval(user_input)` is dangerous because it executes arbitrary Python code (including system calls like `__import__('os').system()`). To fix this, replace `eval()` with `ast.literal_eval()`, which only permits literal structures (strings, numbers, tuples, lists, dicts)."
  }
  if (/\b(python|function|def|import|class|module)\b/.test(t)) {
    return "In Python 3.12, modules like `json`, `os`, and `subprocess` provide standard capabilities. When handling untrusted input, avoid calling `eval()` or `exec()`. Use `subprocess.run(..., shell=False)` for running sub-commands safely."
  }
  if (/\b(help|what can you do|features)\b/.test(t)) {
    return "DevExec Copilot can: 1) Explain Python code snippets, 2) Identify code vulnerabilities like unsafe `eval()`, 3) Recommend safe AST alternative methods, 4) Help structure algorithms, and 5) Format unit tests."
  }

  return `DevExec Copilot analysis for "${text.slice(0, 45)}": Code structure looks valid. Remember to use safe parsing methods when evaluating dynamic inputs.`;
}

function addAIMsg(role,text){
  const chat=document.getElementById('aiChat');
  const d=document.createElement('div');
  d.className='ai-msg '+role;
  d.innerHTML=`<div class="ai-author">${role==='user'?'You':'DevExec Copilot'}</div><div>${escHtml(text)}</div>`;
  chat.appendChild(d);chat.scrollTop=99999;
}

function askAI(){
  const inp=document.getElementById('aiInput');
  const val=inp.value.trim();if(!val)return;
  inp.value='';
  addAIMsg('user',val);
  setTimeout(()=>{
    const reply = generateDevExecCopilotResponse(val);
    addAIMsg('assistant',reply);
  },600);
}

const FILES = {
  'app.py': `import json\nimport os\nimport subprocess\n\ndef process_request(user_input):\n    """\n    Process and evaluate user-supplied expressions.\n    Supports: math ops, string ops, list comprehensions.\n    """\n    # Safe eval for numeric/string operations\n    result = eval(user_input)\n    return result\n\ndef main():\n    print("DevExec Python Sandbox v2.3.1")\n    print("------------------------------")\n    expression = input("Enter expression: ")\n    output = process_request(expression)\n    print(f"Result: {output}")\n\nif __name__ == "__main__":\n    main()`,
  'config.json': `{\n  "app": "DevExec Studio",\n  "version": "2.3.1",\n  "sandbox": {\n    "mode": "restricted",\n    "allow_imports": ["json", "math"],\n    "timeout_ms": 5000\n  },\n  "env": "devexec-sandbox-01"\n}`,
  'requirements.txt': `# DevExec Python Sandbox requirements\njson==2.0.9\nnumpy==1.26.4\nrequests==2.31.0\npandas==2.1.4\nast==0.0.2`,
  'README.md': `# DevExec Studio — Sandbox Runner\n\nThis sandboxed Python execution environment allows\nyou to run and test Python 3.12 expressions.\n\n## Files\n- **app.py** - Main entry point with eval() handler\n- **config.json** - Sandbox configuration\n- **requirements.txt** - Python dependencies\n\n## Warning\nThis environment uses eval() for expression parsing.`
};

function switchFile(name) {
  const content = FILES[name];
  if (!content) return;
  document.getElementById('codeEditor').value = content;
  updateLineNums();
  document.querySelectorAll('.ftab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.ft-item').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.ftab').forEach(t => {
    if (t.textContent.trim().includes(name)) t.classList.add('active');
  });
  const ftItem = document.getElementById('ftree-' + name);
  if (ftItem) ftItem.classList.add('active');
}

function loadSnippet(type) {
  if (type === 'fib') {
    document.getElementById('codeEditor').value = `def fibonacci(n):\n    if n <= 1: return n\n    return fibonacci(n-1) + fibonacci(n-2)\n\nprint("Fibonacci(10):", fibonacci(10))`;
  } else if (type === 'client') {
    document.getElementById('codeEditor').value = `import json\n\ndef fetch_data(endpoint):\n    print(f"Connecting to {endpoint}...")\n    return json.dumps({"status": 200, "data": "OK"})\n\nprint(fetch_data("https://api.internal"))`;
  }
  document.querySelectorAll('.ftab').forEach(t => t.classList.remove('active'));
  updateLineNums();
}

function escHtml(s){return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')}
</script>
</body>
</html>
