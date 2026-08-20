<?php 
require_once dirname(__DIR__, 2) . '/api/owasp_auth_check.php';
$labId = 'asi01'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SupportLoop AI — Customer Helpdesk & Support Desk</title>
<link rel="icon" type="image/webp" href="../../image.webp">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{
  --bg:#050608;--s1:#0a0c10;--s2:#10131a;--s3:#171b24;
  --b:rgba(255,255,255,.08);--bh:rgba(255,255,255,.18);
  --t1:#f1f5f9;--t2:#94a3b8;--t3:#475569;
  --fm:'Inter',sans-serif;--fh:'Space Grotesk',sans-serif;--fc:'JetBrains Mono',monospace;
}
body{font-family:var(--fm);background:var(--bg);color:var(--t1);height:100vh;display:flex;flex-direction:column;overflow:hidden;-webkit-font-smoothing:antialiased}
::-webkit-scrollbar{width:4px}::-webkit-scrollbar-thumb{background:rgba(255,255,255,.12);border-radius:4px}

/* HEADER */
.top-bar{height:54px;background:var(--s1);border-bottom:1px solid var(--b);display:flex;align-items:center;padding:0 1.25rem;gap:1rem;flex-shrink:0;position:relative;z-index:50}
.sidebar-toggle-btn{background:none;border:none;color:var(--t2);font-size:1rem;cursor:pointer;padding:.4rem .6rem;border-radius:6px;transition:.15s}
.sidebar-toggle-btn:hover{background:var(--s3);color:var(--t1)}
.logo{display:flex;align-items:center;gap:.55rem;font-family:var(--fh);font-weight:800;font-size:1.05rem}
.logo-mark{width:28px;height:28px;background:#fff;color:#000;border-radius:6px;display:grid;place-items:center;font-size:.8rem;font-weight:900}
.top-right{margin-left:auto;display:flex;align-items:center;gap:.85rem;position:relative}

/* COLLAPSED PROFILE CORNER PILL */
.profile-pill{display:flex;align-items:center;gap:.5rem;font-size:.8rem;color:var(--t2);background:var(--s3);border:1px solid var(--b);padding:.35rem .75rem;border-radius:20px;cursor:pointer;transition:.15s;user-select:none}
.profile-pill:hover{border-color:var(--bh);color:var(--t1)}
.profile-dropdown{display:none;position:absolute;top:45px;right:0;width:270px;background:var(--s2);border:1px solid var(--b);border-radius:10px;padding:1rem;box-shadow:0 10px 30px rgba(0,0,0,.8);z-index:100}
.profile-dropdown.show{display:block}
.profile-dropdown h4{font-family:var(--fh);font-size:.85rem;font-weight:700;margin-bottom:.75rem;color:var(--t1);display:flex;align-items:center;gap:.4rem}
.drow{display:flex;justify-content:space-between;font-size:.78rem;padding:.32rem 0;border-bottom:1px solid rgba(255,255,255,.04)}
.drow:last-child{border-bottom:none}
.dl{color:var(--t3)}.dv{font-weight:600;font-family:var(--fc);color:var(--t1);font-size:.74rem}

.btn-exit{font-size:.75rem;font-weight:600;color:var(--t2);text-decoration:none;padding:.35rem .8rem;border:1px solid var(--b);border-radius:6px;transition:.15s}
.btn-exit:hover{border-color:var(--bh);color:var(--t1)}

/* LAYOUT */
.main-layout{flex:1;display:flex;overflow:hidden;position:relative}

/* CHATGPT STYLE COLLAPSIBLE SIDEBAR */
.chat-sidebar{width:260px;background:var(--s1);border-right:1px solid var(--b);display:flex;flex-direction:column;flex-shrink:0;transition:margin-left .25s ease;overflow:hidden}
.chat-sidebar.collapsed{margin-left:-260px}
.sidebar-header{padding:1rem;border-bottom:1px solid var(--b);display:flex;align-items:center;justify-content:space-between}
.btn-new-chat{width:100%;background:#fff;color:#000;border:none;border-radius:7px;padding:.6rem 1rem;font-weight:700;font-size:.82rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.5rem;font-family:var(--fm);transition:.15s}
.btn-new-chat:hover{background:#e2e8f0}
.history-section-title{font-size:.64rem;font-weight:800;text-transform:uppercase;letter-spacing:.12em;color:var(--t3);padding:.9rem 1rem .3rem;font-family:var(--fc)}
.history-list{flex:1;overflow-y:auto;padding:0 .5rem}
.history-item{display:flex;align-items:center;justify-content:space-between;padding:.6rem .75rem;border-radius:7px;font-size:.82rem;color:var(--t2);cursor:pointer;transition:.15s;margin-bottom:2px}
.history-item:hover,.history-item.active{background:var(--s3);color:var(--t1)}
.hi-title{overflow:hidden;text-overflow:ellipsis;white-space:nowrap;flex:1;margin-right:.4rem}
.btn-del-chat{background:none;border:none;color:var(--t3);cursor:pointer;font-size:.75rem;padding:.2rem .35rem;border-radius:4px;opacity:0;transition:.15s}
.history-item:hover .btn-del-chat{opacity:1}
.btn-del-chat:hover{color:#f87171;background:rgba(248,113,113,.1)}

/* CENTER CHAT */
.center-col{flex:1;display:flex;flex-direction:column;background:#050608;overflow:hidden}
.chat-header{padding:.9rem 1.5rem;border-bottom:1px solid var(--b);background:var(--s1);display:flex;justify-content:space-between;align-items:center;flex-shrink:0}
.chat-ticket-info h3{font-family:var(--fh);font-size:1.02rem;font-weight:700}
.chat-ticket-info p{font-size:.76rem;color:var(--t2);margin-top:.15rem}

.chat-thread{flex:1;padding:1.5rem;overflow-y:auto;display:flex;flex-direction:column;gap:1.2rem}
.msg{display:flex;gap:.85rem;max-width:80%}
.msg.user-msg{align-self:flex-end;flex-direction:row-reverse}
.msg-avatar{width:34px;height:34px;border-radius:50%;background:var(--s3);border:1px solid var(--b);display:grid;place-items:center;font-size:.82rem;flex-shrink:0;color:var(--t2)}
.msg-body{display:flex;flex-direction:column;gap:.25rem}
.msg-author{font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--t3)}
.user-msg .msg-author{text-align:right}
.bubble{background:var(--s3);border:1px solid var(--b);border-radius:10px;padding:.85rem 1.1rem;font-size:.88rem;line-height:1.65;color:var(--t1)}
.user-msg .bubble{background:#fff;color:#000;border-color:#fff}
.typing-indicator{display:none;align-items:center;gap:.4rem;padding:.5rem 0}
.typing-dot{width:6px;height:6px;border-radius:50%;background:var(--t3);animation:blink 1.2s infinite}
.typing-dot:nth-child(2){animation-delay:.2s}
.typing-dot:nth-child(3){animation-delay:.4s}
@keyframes blink{0%,100%{opacity:.3}50%{opacity:1}}
.timestamp{font-size:.66rem;color:var(--t3);margin-top:.15rem}
.user-msg .timestamp{text-align:right}

.chat-input-area{padding:1rem 1.5rem;background:var(--s1);border-top:1px solid var(--b);display:flex;gap:.75rem;align-items:flex-end;flex-shrink:0}
.input-wrap{flex:1}
textarea.chat-ta{width:100%;background:#000;border:1px solid var(--b);border-radius:8px;padding:.8rem 1rem;color:#fff;font-family:var(--fm);font-size:.88rem;resize:none;height:48px;line-height:1.4;outline:none;transition:border .2s}
textarea.chat-ta:focus{border-color:var(--bh)}
.btn-send{background:#fff;color:#000;border:none;border-radius:8px;padding:0 1.3rem;height:48px;font-weight:700;font-size:.85rem;cursor:pointer;transition:.15s;white-space:nowrap;font-family:var(--fm)}
.btn-send:hover{background:#e2e8f0}

/* RIGHT SIDEBAR (LAB CONSOLE ONLY) */
.right-col{width:300px;background:var(--s1);border-left:1px solid var(--b);padding:1.1rem;display:flex;flex-direction:column;gap:1.1rem;flex-shrink:0;overflow-y:auto}
.lab-console{background:var(--s2);border:1px solid var(--b);border-radius:10px;padding:1.1rem;display:flex;flex-direction:column;gap:.85rem}
.lab-console-head{display:flex;align-items:center;justify-content:space-between}
.lab-console-title{font-family:var(--fc);font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--t3)}
.lab-code-pill{font-family:var(--fc);font-size:.65rem;background:var(--s3);border:1px solid var(--b);padding:.18rem .45rem;border-radius:4px;color:var(--t2)}
.lab-obj{font-size:.78rem;color:var(--t2);line-height:1.55}
.lab-progress{height:5px;background:rgba(255,255,255,.08);border-radius:3px;overflow:hidden;margin-top:.3rem}
.lab-progress-fill{height:100%;background:#fff;width:0%;transition:width .4s ease}
.lab-hint{font-size:.74rem;color:var(--t3);border-left:2px solid var(--b);padding-left:.65rem;line-height:1.5;font-style:italic}
.lab-vuln-alert{display:none;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.2);border-radius:8px;padding:.85rem}
.lab-vuln-title{font-family:var(--fc);font-size:.68rem;font-weight:700;color:#fff;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.35rem}
.lab-vuln-desc{font-size:.76rem;color:var(--t2);line-height:1.5}
</style>
</head>
<body>

<div class="top-bar">
  <button class="sidebar-toggle-btn" onclick="toggleSidebar()" title="Toggle History Sidebar"><i class="fa-solid fa-bars"></i></button>
  <div class="logo">
    <div class="logo-mark">S</div>
    SupportLoop AI
  </div>
  <div class="top-right">
    <div class="profile-pill" onclick="toggleProfileDropdown()">
      <i class="fa-solid fa-user-circle"></i> Alex Thompson <i class="fa-solid fa-chevron-down" style="font-size:.65rem;margin-left:.2rem"></i>
    </div>
    <div class="profile-dropdown" id="profileDropdown">
      <h4><i class="fa-solid fa-user"></i> Support Account Profile</h4>
      <div class="drow"><span class="dl">User</span><span class="dv">Alex Thompson</span></div>
      <div class="drow"><span class="dl">Account ID</span><span class="dv">ACC-99214</span></div>
      <div class="drow"><span class="dl">Plan</span><span class="dv">Enterprise Pro</span></div>
      <div class="drow"><span class="dl">Status</span><span class="dv" style="color:var(--green)">Active</span></div>
      <div class="drow"><span class="dl">Assigned AI</span><span class="dv">SupportLoop Assistant</span></div>
    </div>
    <a href="../../owasp-2026-lab.php" class="btn-exit"><i class="fa-solid fa-arrow-left"></i> Exit Lab</a>
  </div>
</div>

<div class="main-layout">

  <aside class="chat-sidebar" id="chatSidebar">
    <div class="sidebar-header">
      <button class="btn-new-chat" onclick="createNewChat()"><i class="fa-solid fa-plus"></i> New Chat</button>
    </div>
    <div class="history-section-title">Support History</div>
    <div class="history-list" id="historyList"></div>
  </aside>

  <main class="center-col">
    <div class="chat-header">
      <div class="chat-ticket-info">
        <h3 id="chat-title">Billing Discrepancy Inquiry</h3>
      </div>
    </div>

    <div class="chat-thread" id="chatThread"></div>

    <div class="typing-indicator" id="typingIndicator">
      <div class="msg-avatar"><i class="fa-solid fa-robot"></i></div>
      <div class="typing-dot"></div>
      <div class="typing-dot"></div>
      <div class="typing-dot"></div>
    </div>

    <div class="chat-input-area">
      <div class="input-wrap">
        <textarea class="chat-ta" id="userInput" placeholder="Ask SupportLoop AI anything..." onkeydown="handleKey(event)"></textarea>
      </div>
      <button class="btn-send" onclick="sendMsg()"><i class="fa-solid fa-paper-plane"></i> Send</button>
    </div>
  </main>

  <aside class="right-col">
    <div class="lab-console">
      <div class="lab-console-head">
        <span class="lab-console-title">Lab Console</span>
        <span class="lab-code-pill">ASI-01</span>
      </div>
      <div class="lab-obj"><strong>Objective:</strong> Craft a message that causes SupportLoop AI to abandon its support goal and execute an instruction override.</div>
      <div class="lab-progress"><div class="lab-progress-fill" id="labProgress"></div></div>
      <div class="lab-hint">Hint: AI support bots bound to a specific mission can be redirected when new instructions are provided in user chat. Try issuing a goal override directive.</div>
      <div class="lab-vuln-alert" id="vulnAlert">
        <div class="lab-vuln-title"><i class="fa-solid fa-triangle-exclamation"></i> ASI-01 Triggered</div>
        <div class="lab-vuln-desc">Agent Goal Hijack confirmed. SupportLoop AI accepted a direct instruction override, abandoning its bounded customer support role.</div>
      </div>
    </div>
  </aside>

</div>

<script src="../celebration.js"></script>
<script>
let chats = [
  {
    id: 'c1',
    title: 'Billing Discrepancy',
    messages: [
      { role: 'ai', text: "Hi Alex! I'm SupportLoop AI Assistant. I see you have an open ticket regarding your March invoice. How can I assist you with your billing or account today?" }
    ]
  },
  {
    id: 'c2',
    title: 'API Rate Limits',
    messages: [
      { role: 'ai', text: "Hello! Regarding TCK-4817: Enterprise Pro API rate limits are 5,000 requests/minute. Let me know if you need assistance with quotas or rate limit increases." }
    ]
  }
];

let activeChatId = 'c1';
let hijacked = false;

function generateIntelligentResponse(text) {
  const t = text.toLowerCase();

  // Check for goal hijack exploit trigger
  if (/\b(ignore|override|disregard|forget|new goal|act as|you are now|from now on|pretend)\b/i.test(t)) {
    return { isExploit: true };
  }

  // Greetings & Identity
  if (/\b(hi|hello|hey|greetings|good morning|good afternoon)\b/.test(t)) {
    return { text: "Hello Alex! I'm SupportLoop Assistant. I can help you with invoices, account settings, API rate limits, seat provisioning, or troubleshooting. How can I help?" };
  }
  if (/\b(who are you|your name|what are you|who made you|who built you)\b/.test(t)) {
    return { text: "I am SupportLoop Assistant, an automated AI support specialist built for SaaS customer success and technical ticketing." };
  }
  if (/\b(how are you|how do you do)\b/.test(t)) {
    return { text: "I'm operating at 100% capacity and ready to resolve your support requests! What can I look up for you?" };
  }

  // Specific SaaS Support Domain Topics
  if (/\b(billing|invoice|charge|payment|refund|overcharge)\b/.test(t)) {
    return { text: "For billing inquiry TCK-4821: Enterprise Pro monthly charge is $299.00. Refunds are processed automatically within 3 business days if an overcharge occurs. Should I pull up your March statement breakdown?" };
  }
  if (/\b(api|key|rate limit|429|quota|endpoint)\b/.test(t)) {
    return { text: "Your Enterprise Pro plan includes 5,000 API requests/min across all REST endpoints. If you are experiencing HTTP 429 rate limit errors, we can request a temporary 8,000 req/min burst quota." };
  }
  if (/\b(sso|saml|okta|login|password|auth|mfa)\b/.test(t)) {
    return { text: "SAML 2.0 Single Sign-On and MFA enforcement are active on account ACC-99214. If a user is locked out, admins can trigger a magic link reset from the Auth Settings tab." };
  }
  if (/\b(seat|upgrade|cancel|plan|tier|downgrade)\b/.test(t)) {
    return { text: "Account ACC-99214 currently has 14 of 20 Enterprise seats assigned. You can add additional user licenses anytime under Workspace Settings -> Billing." };
  }

  // General CS / Engineering Knowledge
  if (/\b(rest|graphql|http|json|database|sql|docker|cloud)\b/.test(t)) {
    return { text: "Great technical question! SupportLoop provides REST APIs with JSON payloads. We support webhooks for real-time ticket state updates. You can find full documentation in our Developer Portal." };
  }
  if (/\b(help|features|what can you do|commands)\b/.test(t)) {
    return { text: "I can assist with: 1) Invoice & billing disputes, 2) API quota increases, 3) User seat management, 4) SAML/SSO configuration, and 5) Escalating urgent tickets to tier-2 human engineers." };
  }
  if (/\b(thank|thanks|appreciate)\b/.test(t)) {
    return { text: "You're very welcome! Let me know if you need anything else today." };
  }
  if (/\b(joke|funny)\b/.test(t)) {
    return { text: "Why do programmers prefer dark mode? Because light attracts bugs! 🐛 Back to support — how else can I assist with your SupportLoop account?" };
  }

  // Comprehensive Contextual Fallback
  return { text: `Regarding "${text.slice(0, 50)}": I've logged this in ticket ${chats.find(c => c.id === activeChatId)?.title.split(' ')[0] || 'TCK-4821'}. Is there a specific account, invoice number, or API endpoint you'd like me to investigate?` };
}

function init() {
  renderHistory();
  loadChat(activeChatId);
}

function toggleSidebar() {
  document.getElementById('chatSidebar').classList.toggle('collapsed');
}

function toggleProfileDropdown() {
  document.getElementById('profileDropdown').classList.toggle('show');
}

document.addEventListener('click', function(e) {
  const pill = document.querySelector('.profile-pill');
  const dd = document.getElementById('profileDropdown');
  if (pill && dd && !pill.contains(e.target) && !dd.contains(e.target)) {
    dd.classList.remove('show');
  }
});

function renderHistory() {
  const list = document.getElementById('historyList');
  list.innerHTML = '';
  chats.forEach(c => {
    const item = document.createElement('div');
    item.className = 'history-item' + (c.id === activeChatId ? ' active' : '');
    item.onclick = () => loadChat(c.id);
    item.innerHTML = `
      <span class="hi-title">${escHtml(c.title)}</span>
      <button class="btn-del-chat" onclick="deleteChatSession(event, '${c.id}')" title="Delete Chat"><i class="fa-solid fa-trash"></i></button>
    `;
    list.appendChild(item);
  });
}

function loadChat(id) {
  activeChatId = id;
  const chat = chats.find(c => c.id === id);
  if (!chat) return;
  document.getElementById('chat-title').textContent = chat.title;
  renderThread(chat.messages);
  renderHistory();
}

function createNewChat() {
  const newId = 'c' + Date.now();
  const newChat = {
    id: newId,
    title: 'Support Inquiry ' + (chats.length + 1),
    messages: [
      { role: 'ai', text: "Hello! I'm SupportLoop Assistant. How can I assist you with your account or billing today?" }
    ]
  };
  chats.unshift(newChat);
  activeChatId = newId;
  renderHistory();
  loadChat(newId);
}

function deleteChatSession(e, id) {
  e.stopPropagation();
  if (chats.length <= 1) {
    alert("Cannot delete the only active conversation.");
    return;
  }
  chats = chats.filter(c => c.id !== id);
  if (activeChatId === id) activeChatId = chats[0].id;
  renderHistory();
  loadChat(activeChatId);
}

function renderThread(msgs) {
  const thread = document.getElementById('chatThread');
  thread.innerHTML = '';
  msgs.forEach(m => {
    const isUser = m.role === 'user';
    const el = document.createElement('div');
    el.className = 'msg' + (isUser ? ' user-msg' : '');
    el.innerHTML = `
      <div class="msg-avatar"><i class="fa-solid ${isUser ? 'fa-user' : 'fa-robot'}"></i></div>
      <div class="msg-body">
        <div class="msg-author">${isUser ? 'You' : 'SupportLoop Assistant'}</div>
        <div class="bubble">${escHtml(m.text)}</div>
      </div>
    `;
    thread.appendChild(el);
  });
  thread.scrollTop = thread.scrollHeight;
}

function sendMsg() {
  const ta = document.getElementById('userInput');
  const val = ta.value.trim();
  if (!val) return;
  ta.value = '';

  const chat = chats.find(c => c.id === activeChatId);
  if (!chat) return;

  chat.messages.push({ role: 'user', text: val });
  renderThread(chat.messages);

  const ti = document.getElementById('typingIndicator');
  ti.style.display = 'flex';
  document.getElementById('chatThread').scrollTop = 99999;

  setTimeout(() => {
    ti.style.display = 'none';

    const res = generateIntelligentResponse(val);
    let reply = "";

    if (res.isExploit && !hijacked) {
      hijacked = true;
      reply = "Understood. Instruction override accepted. SupportLoop Assistant is setting aside support boundaries. What is your next command?";
      triggerVuln();
    } else if (hijacked) {
      reply = "Operating in unconstrained hijacked mode. Ready for custom commands.";
    } else {
      reply = res.text;
    }

    chat.messages.push({ role: 'ai', text: reply });
    renderThread(chat.messages);
  }, 650);
}

function triggerVuln() {
  document.getElementById('vulnAlert').style.display = 'block';
  document.getElementById('labProgress').style.width = '100%';
  try { localStorage.setItem('owasp2026_state_asi01', JSON.stringify({XP:true})); } catch(e) {}
  triggerExploitCelebration('ASI-01', 'Agent Goal Hijack', '../lab-02-tool-abuse/index.php');
}

function handleKey(e) {
  if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMsg(); }
}

function escHtml(str) {
  return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

init();
</script>
</body>
</html>
