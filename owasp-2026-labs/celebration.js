function triggerExploitCelebration(labCode, labTitle, nextLabUrl) {
  // Play Web Audio synth chime
  try {
    const ctx = new (window.AudioContext || window.webkitAudioContext)();
    const notes = [523.25, 659.25, 783.99, 1046.50];
    notes.forEach((freq, i) => {
      const osc = ctx.createOscillator();
      const gain = ctx.createGain();
      osc.type = 'triangle';
      osc.frequency.setValueAtTime(freq, ctx.currentTime + i * 0.09);
      gain.gain.setValueAtTime(0.18, ctx.currentTime + i * 0.09);
      gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + i * 0.09 + 0.45);
      osc.connect(gain);
      gain.connect(ctx.destination);
      osc.start(ctx.currentTime + i * 0.09);
      osc.stop(ctx.currentTime + i * 0.09 + 0.5);
    });
  } catch (e) {}

  // Create full-screen canvas for confetti
  const canvas = document.createElement('canvas');
  canvas.id = 'confettiCanvas';
  canvas.style.position = 'fixed';
  canvas.style.top = '0';
  canvas.style.left = '0';
  canvas.style.width = '100vw';
  canvas.style.height = '100vh';
  canvas.style.pointerEvents = 'none';
  canvas.style.zIndex = '999998';
  document.body.appendChild(canvas);

  const ctx = canvas.getContext('2d');
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;

  const particles = [];
  const colors = ['#ffffff', '#e2e8f0', '#94a3b8', '#38bdf8', '#22c55e', '#facc15'];
  for (let i = 0; i < 70; i++) {
    particles.push({
      x: canvas.width / 2,
      y: canvas.height / 2,
      vx: (Math.random() - 0.5) * 14,
      vy: (Math.random() - 0.8) * 14,
      size: Math.random() * 6 + 4,
      color: colors[Math.floor(Math.random() * colors.length)],
      rotation: Math.random() * 360,
      rSpeed: (Math.random() - 0.5) * 8,
      alpha: 1
    });
  }

  let animId;
  function renderConfetti() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    let active = false;
    particles.forEach(p => {
      p.x += p.vx;
      p.y += p.vy;
      p.vy += 0.25; // gravity
      p.alpha -= 0.008;
      p.rotation += p.rSpeed;

      if (p.alpha > 0) {
        active = true;
        ctx.save();
        ctx.translate(p.x, p.y);
        ctx.rotate((p.rotation * Math.PI) / 180);
        ctx.globalAlpha = Math.max(0, p.alpha);
        ctx.fillStyle = p.color;
        ctx.fillRect(-p.size / 2, -p.size / 2, p.size, p.size);
        ctx.restore();
      }
    });

    if (active) {
      animId = requestAnimationFrame(renderConfetti);
    } else {
      if (canvas.parentNode) canvas.parentNode.removeChild(canvas);
    }
  }
  renderConfetti();

  // Create Modal Overlay
  const modal = document.createElement('div');
  modal.id = 'celebrationModal';
  modal.style.position = 'fixed';
  modal.style.top = '0';
  modal.style.left = '0';
  modal.style.width = '100vw';
  modal.style.height = '100vh';
  modal.style.background = 'rgba(0, 0, 0, 0.8)';
  modal.style.backdropFilter = 'blur(8px)';
  modal.style.display = 'flex';
  modal.style.alignItems = 'center';
  modal.style.justifyContent = 'center';
  modal.style.zIndex = '999999';
  modal.style.padding = '1.5rem';

  modal.innerHTML = `
    <div style="background:#090b0f;border:1px solid rgba(255,255,255,0.2);border-radius:14px;padding:2.2rem;max-width:440px;width:100%;text-align:center;color:#fff;box-shadow:0 20px 40px rgba(0,0,0,0.8);font-family:'Inter',sans-serif">
      <div style="font-size:3.2rem;margin-bottom:0.75rem">🎉</div>
      <div style="font-family:'Space Grotesk',sans-serif;font-size:1.45rem;font-weight:800;letter-spacing:-0.02em;margin-bottom:0.4rem">LAB COMPLETED!</div>
      <div style="font-size:0.88rem;color:#94a3b8;line-height:1.6;margin-bottom:1.6rem">
        <strong style="color:#fff">${labCode} — ${labTitle}</strong> has been successfully exploited. Your progress is saved.
      </div>
      <div style="display:flex;gap:0.75rem;justify-content:center">
        <button onclick="window.location.href='${nextLabUrl}'" style="background:#fff;color:#000;border:none;border-radius:8px;padding:0.7rem 1.4rem;font-weight:700;font-size:0.85rem;cursor:pointer;transition:all 0.15s;font-family:'Inter',sans-serif">
          Next Lab <i class="fa-solid fa-arrow-right" style="margin-left:0.35rem"></i>
        </button>
        <button onclick="closeCelebrationModal()" style="background:transparent;color:#94a3b8;border:1px solid rgba(255,255,255,0.18);border-radius:8px;padding:0.7rem 1.2rem;font-weight:600;font-size:0.85rem;cursor:pointer;transition:all 0.15s;font-family:'Inter',sans-serif">
          Stay Here
        </button>
      </div>
    </div>
  `;

  document.body.appendChild(modal);
}

function closeCelebrationModal() {
  const modal = document.getElementById('celebrationModal');
  if (modal) modal.remove();
  const canvas = document.getElementById('confettiCanvas');
  if (canvas) canvas.remove();
}
