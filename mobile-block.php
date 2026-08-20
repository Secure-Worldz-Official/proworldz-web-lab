<div id="mobile-restriction-overlay" class="mobile-restriction-overlay">
    <div class="restriction-content">
        <div class="restriction-icon">
            <i class="fas fa-desktop"></i>
        </div>
        <h1>PC/Laptop Required</h1>
        <p>To ensure the best possible experience and full access to our interactive lab environments, please open this platform on a <span>Laptop or PC</span>.</p>
        <div class="restriction-footer">
            <div class="badge">Desktop Only</div>
        </div>
    </div>
</div>

<style>
.mobile-restriction-overlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 10000;
    background: radial-gradient(circle at center, #1a1a1a 0%, #000000 100%);
    align-items: center;
    justify-content: center;
    padding: 2rem;
    text-align: center;
}

.restriction-content {
    max-width: 400px;
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 24px;
    padding: 3rem 2rem;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    animation: fadeInScale 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.restriction-icon {
    width: 80px;
    height: 80px;
    background: rgba(255, 42, 47, 0.1);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
}

.restriction-icon i {
    font-size: 2.5rem;
    color: #ff2a2f;
    animation: pulseIcon 2s infinite;
}

.restriction-content h1 {
    font-size: 1.75rem;
    font-weight: 800;
    margin-bottom: 1rem;
    background: linear-gradient(to right, #fff, #a0a0a0);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-family: 'Space Grotesk', sans-serif;
}

.restriction-content p {
    color: #a0a0a0;
    line-height: 1.6;
    margin-bottom: 2rem;
    font-size: 1rem;
}

.restriction-content p span {
    color: #ff2a2f;
    font-weight: 700;
}

.restriction-footer .badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: rgba(255, 42, 47, 0.1);
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #ff2a2f;
    border: 1px solid rgba(255, 42, 47, 0.3);
}

@keyframes fadeInScale {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}

@keyframes pulseIcon {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.1); opacity: 0.8; }
}

@media (max-width: 768px) {
    .mobile-restriction-overlay {
        display: flex;
    }
    body {
        overflow: hidden !important;
        position: fixed;
        width: 100%;
    }
}
</style>
