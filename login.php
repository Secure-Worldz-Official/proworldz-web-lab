<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
  <meta charset="UTF-8">
  <title>Login | Secure WorldzZ</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/webp" href="image.webp">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
            --gap: 1.5rem;
            --sides: 1.5rem;
        --primary-red: #ff2a2f;
        --primary-red-hover: #ff2a2f;
        --secondary-red: #ff2a2f;
        --accent-red: #ff2a2f;
        --dark-bg: #000000;
        --darker-bg: #000000;
        --card-bg: #080808;
        --card-hover: #080808;
        --text-primary: #ffffff;
        --text-secondary: #a3a3a3;
        --text-muted: #737373;
        --border-color: rgba(139, 12, 16, 0.1);
        --border-hover: rgba(255, 255, 255, 0.2);
        --success: #ffffff;
        --danger: #ffffff;
        
        --shadow-glow: 0 0 40px rgba(139, 12, 16, 0.1);
        --shadow-intense: 0 20px 60px rgba(139, 12, 16, 0.15);
        --transition-smooth: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        --transition-fast: all 0.2s ease;
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: var(--dark-bg);
        color: var(--text-primary);
        line-height: 1.6;
        overflow-x: hidden;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        position: relative;
    }

    h1, h2, h3, h4 {
        font-family: 'Space Grotesk', sans-serif;
        font-weight: 700;
        line-height: 1.2;
    }

    .auth-container {
        width: 100%;
        max-width: 420px;
        position: relative;
        z-index: 2;
    }

    .auth-box {
        background: var(--card-bg);
        padding: 3rem;
        border-radius: 20px;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-glow);
        position: relative;
        overflow: hidden;
        transition: var(--transition-smooth);
    }

    .brand {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .logo-img {
        width: 150px;
        height: 150px;
        object-fit: contain;
        margin: 0 auto 1.5rem;
        filter: brightness(1.2);
        transition: var(--transition-smooth);
    }

    .logo-img:hover {
        transform: rotate(10deg) scale(1.1);
        filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
    }

    .brand h1 {
        font-size: 2rem;
        font-weight: 900;
        background: linear-gradient(135deg, var(--text-primary) 0%, var(--secondary-red) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }

    .brand p {
        color: var(--text-secondary);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    .form-group {
        position: relative;
        margin-bottom: 1.75rem;
    }

    .form-group input {
        width: 100%;
        padding: 1rem 1.25rem;
        background: rgba(139, 12, 16, 0.05);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        color: var(--text-primary);
        font-size: 0.95rem;
        font-family: 'Inter', sans-serif;
        transition: var(--transition-smooth);
        padding-right: 3rem;
    }

    .form-group label {
        position: absolute;
        top: 1rem;
        left: 1.25rem;
        color: var(--text-secondary);
        font-size: 0.95rem;
        pointer-events: none;
        transition: var(--transition-smooth);
        background: transparent;
    }

    .form-group input:focus + label,
    .form-group input:not(:placeholder-shown) + label {
        top: -0.75rem;
        left: 0.75rem;
        font-size: 0.75rem;
        color: var(--text-primary);
        padding: 0 0.5rem;
        background: var(--card-bg);
    }

    .password-toggle {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--text-secondary);
        cursor: pointer;
        font-size: 1rem;
        padding: 0.25rem;
        transition: var(--transition-fast);
    }

    .btn-login {
        width: 100%;
        padding: 1rem;
        border: none;
        border-radius: 10px;
        background: linear-gradient(135deg, #ffffff 0%, #d0d0d0 100%);
        color: #000000;
        font-size: 1rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        cursor: pointer;
        margin-top: 0.5rem;
        transition: var(--transition-smooth);
        font-family: 'Space Grotesk', sans-serif;
        position: relative;
        overflow: hidden;
    }

    .error {
        text-align: center;
        color: var(--text-primary);
        font-size: 0.875rem;
        margin: 0.75rem 0;
        display: none;
        background: rgba(139, 12, 16, 0.05);
        padding: 0.75rem;
        border-radius: 8px;
        border: 1px solid rgba(139, 12, 16, 0.1);
    }

    .loading {
        text-align: center;
        color: var(--text-primary);
        font-size: 0.875rem;
        margin: 0.75rem 0;
        display: none;
    }

    .loading::after {
        content: '';
        display: inline-block;
        width: 1rem;
        height: 1rem;
        margin-left: 0.5rem;
        border: 2px solid var(--border-color);
        border-top-color: var(--text-primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        vertical-align: middle;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    @media (max-width: 768px) {
        .auth-box { padding: 2.5rem; }
        .logo-img { width: 70px; height: 70px; }
        .brand h1 { font-size: 1.75rem; }
    }

    @media (max-width: 480px) {
        body { padding: 15px; }
        .auth-box { padding: 2rem; }
        .brand h1 { font-size: 1.5rem; }
        .brand p { font-size: 0.75rem; }
        .form-group input { padding: 0.875rem 1rem; font-size: 0.875rem; }
        .btn-login { padding: 0.875rem; font-size: 0.875rem; }
    }

    body, h1, h2, h3, h4, h5, h6, p, span, div, li, a, button, input {
        font-weight: 700 !important;
    }
</style>
<link rel="stylesheet" href="app-theme-overrides.css?v=20260817">
</head>
<body>
  <div class="auth-container">
    <div class="auth-box">
      <div class="brand">
        <img src="images/eaglone/e-welcome-gen.png" alt="Secure Worldzz Logo" class="logo-img" loading="lazy">
        <h1>Secure Worldz</h1>
        <p>Access your professional learning dashboard</p>
      </div>

      <div class="form-group">
        <input type="email" id="mail-login" name="mail-login" placeholder=" " required>
        <label>Email Address</label>
      </div>

      <div class="form-group">
        <input type="password" id="passw-login" name="passw-login" placeholder=" " required>
        <label>Password</label>
        <button type="button" class="password-toggle" id="togglePassword">
          <i class="fas fa-eye"></i>
        </button>
      </div>

      <div class="error" id="errorMessage">
        <i class="fas fa-exclamation-circle"></i> Invalid credentials. Please try again.
      </div>
      
      <div class="loading" id="loading">
        Authenticating...
      </div>

      <button type="submit" class="btn-login" onclick="login()" id="loginBtn">
        <i class="fas fa-sign-in-alt"></i> Login
      </button>
    </div>
  </div>

<script>
  document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('passw-login');
    const icon = this.querySelector('i');
    const type = passwordInput.type === 'password' ? 'text' : 'password';
    passwordInput.type = type;
    icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
    this.style.color = type === 'password' ? 'var(--text-secondary)' : 'var(--text-primary)';
  });

  function login() {
    const mail = document.getElementById('mail-login').value.trim();
    const pass = document.getElementById('passw-login').value;
    if (!mail || !pass) return;

    const datas = new FormData();
    datas.append('mail-login', mail);
    datas.append('passw-login', pass);

    document.getElementById('loading').style.display = 'block';
    document.getElementById('loginBtn').disabled = true;
    document.getElementById('errorMessage').style.display = 'none';

    fetch('api/auth/login.php', {
      method: 'POST',
      body: datas,
      credentials: 'include'
    })
    .then(res => res.json())
    .then(data => {
      if (data['result'] === 203) {
        alert('Invalid email found');
        showAccessDenied();
      } else if (data['result'] === null) {
        showAccessDenied();
      } else if (data['result'] !== null && typeof data['result'] === 'string' && data['result'].length > 0) {
        document.getElementById('loginBtn').innerHTML = '<i class="fas fa-check"></i> Access Granted';
        document.getElementById('loginBtn').style.background = 'var(--success)';
        const urlParams = new URLSearchParams(window.location.search);
        const redirectParam = urlParams.get('redirect');
        const allowedTargets = ['owasp-2026-landing.php', 'owasp-2026-lab.php', 'owasp-2026-lab-guide.php', 'swa-lab.php', 'lab.php', 'dashboard.php'];
        const targetUrl = (redirectParam && (allowedTargets.includes(redirectParam) || redirectParam.startsWith('owasp-2026'))) ? redirectParam : 'dashboard.php';
        setTimeout(() => { location.replace(targetUrl); }, 1000);
      } else {
        showAccessDenied();
      }
    })
    .catch(() => showAccessDenied())
    .finally(() => {
      document.getElementById('loading').style.display = 'none';
      document.getElementById('loginBtn').disabled = false;
    });
  }

  function showAccessDenied() {
    const btn = document.getElementById('loginBtn');
    btn.innerHTML = '<i class="fas fa-times"></i> Access Denied';
    btn.style.background = 'var(--danger)';
    setTimeout(() => {
      btn.innerHTML = 'Login';
      btn.style.background = '';
    }, 2000);
  }

  document.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      const loginBtn = document.getElementById('loginBtn');
      if (!loginBtn.disabled) login();
    }
  });
</script>
</body>
</html>
