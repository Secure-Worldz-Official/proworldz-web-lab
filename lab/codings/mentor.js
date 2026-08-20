document.addEventListener('DOMContentLoaded', () => {
    // Inject the CSS for the mentor pop-up
    const style = document.createElement('style');
    style.innerHTML = `
        .mentor-container {
            position: fixed;
            bottom: -150px;
            right: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
            z-index: 10000;
            transition: bottom 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            pointer-events: none;
        }

        .mentor-container.show {
            bottom: 30px;
        }

        .mentor-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 3px solid #6366f1;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            background: #1a1d24;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mentor-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .mentor-speech {
            background: #1a1d24;
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 12px;
            padding: 12px 20px;
            color: #f8fafc;
            font-size: 14px;
            max-width: 250px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            position: relative;
            font-weight: 500;
            line-height: 1.4;
        }

        .mentor-speech::after {
            content: '';
            position: absolute;
            right: -8px;
            top: 50%;
            transform: translateY(-50%);
            border-top: 8px solid transparent;
            border-bottom: 8px solid transparent;
            border-left: 8px solid #1a1d24;
        }
        
        .mentor-speech::before {
            content: '';
            position: absolute;
            right: -10px;
            top: 50%;
            transform: translateY(-50%);
            border-top: 10px solid transparent;
            border-bottom: 10px solid transparent;
            border-left: 10px solid rgba(99, 102, 241, 0.3);
        }

        @media (max-width: 768px) {
            .mentor-container {
                right: 15px;
            }
            .mentor-speech {
                max-width: 200px;
                font-size: 12px;
            }
            .mentor-avatar {
                width: 50px;
                height: 50px;
            }
        }
    `;
    document.head.appendChild(style);

    // Create the mentor elements
    const mentorContainer = document.createElement('div');
    mentorContainer.className = 'mentor-container';

    const speechBubble = document.createElement('div');
    speechBubble.className = 'mentor-speech';

    const avatarWrapper = document.createElement('div');
    avatarWrapper.className = 'mentor-avatar';

    const avatarImg = document.createElement('img');
    // Using current session user avatar via our existing endpoint
    avatarImg.src = '../../api/get_avatar_img.php';
    avatarImg.alt = 'Your Avatar';
    avatarImg.onerror = () => { avatarImg.src = '../../image.webp'; };

    avatarWrapper.appendChild(avatarImg);
    mentorContainer.appendChild(speechBubble);
    mentorContainer.appendChild(avatarWrapper);
    document.body.appendChild(mentorContainer);

    const messages = [
        "Great logic! Let's see if this works.",
        "You're doing awesome. Compiling your code...",
        "Executing masterpiece! Fingers crossed.",
        "Hold tight, running your creation...",
        "Let the code magic happen!",
        "Every error is a step towards perfection.",
        "Computing your genius...",
        "Awesome! Your code is processing."
    ];

    let hideTimeout;

    function showMentor() {
        const msg = messages[Math.floor(Math.random() * messages.length)];
        speechBubble.textContent = msg;

        // Remove and re-add class to restart animation if already showing
        mentorContainer.classList.remove('show');

        // Use requestAnimationFrame for smooth transition restart
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                mentorContainer.classList.add('show');
            });
        });

        clearTimeout(hideTimeout);
        hideTimeout = setTimeout(() => {
            mentorContainer.classList.remove('show');
        }, 3500);
    }

    // Attach to Run Button click
    const runBtn = document.getElementById('runBtn');
    if (runBtn) {
        runBtn.addEventListener('click', showMentor);
    }

    // Attach to code area shortcut (Ctrl+Enter)
    const codeArea = document.getElementById('code');
    if (codeArea) {
        codeArea.addEventListener('keydown', (e) => {
            if (e.ctrlKey && e.key === 'Enter') {
                showMentor();
            }
        });
    }
});
