
function validateTask(taskName, code, output) {
    const codeLower = code.toLowerCase();
    const outputTrimmed = output.trim();
    const outputLower = outputTrimmed.toLowerCase();

    let isValid = false;
    let feedback = "";

    switch (taskName) {
        case "Longest Palindromic Substring":
            // Check if code looks like it's solving it and output has a palindrome
            // To be truly correct, we'd need to run it against multiple test cases
            // For now, let's check for logic keywords and a sample output
            isValid = (codeLower.includes('for') || codeLower.includes('while')) &&
                (codeLower.includes('if') || codeLower.includes('substr')) &&
                outputTrimmed.length > 0;
            break;

        case "N-Queens Problem":
            // For N=8, the answer is 92
            isValid = codeLower.includes('queen') && (outputTrimmed.includes('92') || outputLower.includes('ninety two'));
            break;

        case "Merge Sorted Lists":
            isValid = (codeLower.includes('while') || codeLower.includes('for')) &&
                (codeLower.includes('if') || codeLower.includes('else')) &&
                outputTrimmed.length > 0;
            break;

        case "Sudoku Solver":
            isValid = (codeLower.includes('solve') || codeLower.includes('backtrack')) &&
                outputTrimmed.length > 0;
            break;

        case "Valid Regular Expression":
            isValid = codeLower.includes('match') && (codeLower.includes('.') || codeLower.includes('*'));
            break;

        case "Maximum Subarray Sum":
            isValid = (codeLower.includes('max') || codeLower.includes('sum')) &&
                (codeLower.includes('for') || codeLower.includes('kadane'));
            break;

        case "Dijkstra Algorithm":
            isValid = codeLower.includes('graph') && (codeLower.includes('dist') || codeLower.includes('weight'));
            break;

        case "Balanced Binary Tree":
            isValid = (codeLower.includes('tree') || codeLower.includes('node')) && codeLower.includes('height');
            break;

        case "LRU Cache":
            isValid = (codeLower.includes('get') || codeLower.includes('put') || codeLower.includes('set')) &&
                (codeLower.includes('cache') || codeLower.includes('capacity'));
            break;

        default:
            // Generic validation for simple tasks
            if (taskName.toLowerCase().includes('print')) {
                isValid = outputTrimmed.length > 0;
            } else {
                isValid = code.length > 20 && outputTrimmed.length > 0;
            }
    }

    if (isValid) {
        completeTaskByValidator(taskName, currentTask.coins);
    } else {
        alert("Validation failed. Please ensure your code correctly solves the tricky task: " + taskName);
    }
}

function completeTaskByValidator(taskName, coins) {
    const datas = new FormData();
    datas.append('task-name', String(taskName));
    datas.append('task-coin', Number(coins));

    fetch('../../api/update_task.php', {
        method: 'POST',
        body: datas
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showSuccessPopup(coins);
                localStorage.removeItem('tasksinfo');
                currentTask = null;
                const banner = document.getElementById('pending-assign-banner');
                if (banner) banner.innerText = '';
                const runBtn = document.getElementById('runBtn');
                if (runBtn) runBtn.innerHTML = '<i class="fas fa-play"></i> Execute Code';
            } else {
                console.error('Failed to update task:', data);
                alert('Task completed but failed to update coins. Please try again.');
            }
        })
        .catch(err => {
            console.error('Error updating task:', err);
            alert('Connection error. Task completed but coins might not have been added.');
        });
}

function showSuccessPopup(coins) {
    const overlay = document.createElement('div');
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.85);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        animation: fadeIn 0.3s ease-out;
    `;

    const popup = document.createElement('div');
    popup.style.cssText = `
        background: linear-gradient(145deg, #1a1d24, #0f1117);
        border-radius: 20px;
        padding: 40px 50px;
        text-align: center;
        max-width: 450px;
        width: 90%;
        border: 2px solid #6366f1;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5),
                    0 0 0 1px rgba(99, 102, 241, 0.1),
                    inset 0 0 20px rgba(99, 102, 241, 0.1);
        position: relative;
        overflow: hidden;
        animation: slideUp 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    `;

    const title = document.createElement('h2');
    title.textContent = '🎉 Mission Accomplished!';
    title.style.cssText = `
        color: #f8fafc;
        font-size: 28px;
        margin: 0 0 15px 0;
        font-weight: 700;
        background: linear-gradient(135deg, #f8fafc, #94a3b8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    `;

    const message = document.createElement('p');
    message.textContent = 'Tricky task solved! Eagle coins added to your secure wallet.';
    message.style.cssText = `color: #94a3b8; font-size: 16px; margin-bottom: 30px;`;

    const coinInfo = document.createElement('div');
    coinInfo.innerHTML = `<span style="color: #fbbf24; font-size: 42px; font-weight: 800;">+${coins}</span> <span style="color: #f8fafc; font-size: 20px;">Eagle Coins</span>`;
    coinInfo.style.marginBottom = "30px";

    const btn = document.createElement('button');
    btn.textContent = 'CONTINUE MISSIONS';
    btn.style.cssText = `
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
        border: none;
        padding: 15px 30px;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        width: 100%;
    `;
    btn.onclick = () => {
        document.body.removeChild(overlay);
        window.location.href = '../../tasks.php';
    };

    popup.appendChild(title);
    popup.appendChild(message);
    popup.appendChild(coinInfo);
    popup.appendChild(btn);
    overlay.appendChild(popup);
    document.body.appendChild(overlay);

    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(40px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    `;
    document.head.appendChild(style);
}
