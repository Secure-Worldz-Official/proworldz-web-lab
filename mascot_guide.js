document.addEventListener('DOMContentLoaded', () => {
    // Load CSS
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = 'mascot_guide.css';
    document.head.appendChild(link);
  
    // Create container
    const container = document.createElement('div');
    container.className = 'mascot-container';
    
    // Create Bubble
    const bubble = document.createElement('div');
    bubble.className = 'mascot-bubble';
    
    bubble.innerHTML = `
        <div class="mascot-header">
            <div class="mascot-title"><i class="fas fa-robot"></i> Compiler Guide</div>
            <button class="mascot-close"><i class="fas fa-times"></i></button>
        </div>
        <ul class="mascot-steps">
            <li>Write your code in the editor on the left panel.</li>
            <li>Press <strong>Ctrl + Enter</strong> or click <strong>Run</strong> to execute the compiler.</li>
            <li>View your output or errors on the right panel.</li>
            <li>Use keyboard shortcuts if needed for faster coding.</li>
        </ul>
    `;
    
    // Create Avatar
    const avatar = document.createElement('div');
    avatar.className = 'mascot-avatar-btn mascot-pulse';
    avatar.innerHTML = '🤖';
    
    container.appendChild(bubble);
    container.appendChild(avatar);
    document.body.appendChild(container);
    
    let isOpen = false;
    
    const toggleMascot = () => {
        isOpen = !isOpen;
        if(isOpen) {
            bubble.classList.add('show');
            avatar.classList.remove('mascot-pulse');
        } else {
            bubble.classList.remove('show');
        }
    };
  
    avatar.addEventListener('click', toggleMascot);
    
    const closeBtn = bubble.querySelector('.mascot-close');
    closeBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        isOpen = false;
        bubble.classList.remove('show');
    });
  
    // Auto-show for 8 seconds on load
    setTimeout(() => {
        if(!isOpen) {
            toggleMascot();
            setTimeout(() => {
                if(isOpen) toggleMascot();
            }, 8000);
        }
    }, 1000);
});
