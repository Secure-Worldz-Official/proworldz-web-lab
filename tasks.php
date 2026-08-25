<?php
require_once 'api/auth_check.php';

/* Each tasksdb row represents one programming task. */
$databaseTasks = [];
$totalTaskCount = 0;
$completedTaskCount = 0;
$pendingTaskCount = 0;

if (isset($db) && $db->con instanceof mysqli) {
    $tasksResult = $db->con->query(
        'SELECT id, tasks, description, completed FROM tasksdb ORDER BY id'
    );

    if ($tasksResult) {
        while ($taskRow = $tasksResult->fetch_assoc()) {
            // Placeholder values until tasksdb has difficulty and EC reward columns.
            $databaseTasks[] = [
                'id' => (string) ($taskRow['id'] ?? ''),
                'title' => (string) ($taskRow['tasks'] ?? ''),
                'description' => (string) ($taskRow['description'] ?? ''),
                'reward' => 10,
                'completed' => isset($taskRow['completed']) && (int) $taskRow['completed'] === 1,
            ];
        }
    }

    $statsResult = $db->con->query(
        'SELECT
            COUNT(*) AS total,
            COALESCE(SUM(CASE WHEN completed = 1 THEN 1 ELSE 0 END), 0) AS completed,
            COALESCE(SUM(CASE WHEN completed = 0 OR completed IS NULL THEN 1 ELSE 0 END), 0) AS pending
         FROM tasksdb'
    );

    if ($statsResult && ($stats = $statsResult->fetch_assoc())) {
        $totalTaskCount = (int) $stats['total'];
        $completedTaskCount = (int) $stats['completed'];
        $pendingTaskCount = (int) $stats['pending'];
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programming Tasks | Secure Worldz Academy</title>
    <link rel="icon" type="image/webp" href="image.webp">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="app-theme-overrides.css?v=20260817">
    <script src="api/includes/presence_realtime.js?v=20260320c" defer></script>
    <style>
        :root {
            --primary: #ff2a2f;
            --muted-fg: #94a3b8;
            --border-subtle: rgba(255, 255, 255, 0.08);
            --bg-card: rgba(16, 21, 31, 0.85);
        }

        * { box-sizing: border-box; }
        body { margin: 0; overflow-x: hidden; background: #07090e; color: #ffffff; font-family: 'Roboto Mono', monospace; }
        .tasks-page { gap: 1.75rem; }
        .tasks-page-header { flex-shrink: 0; }

        .tasks-page-header__content { display: flex; align-items: center; gap: 0.75rem; padding: 1rem; }
        .tasks-page-header__icon {
            display: inline-flex; width: 2.25rem; height: 2.25rem; flex: 0 0 2.25rem; align-items: center; justify-content: center;
            border-radius: 0.375rem; background: var(--primary); color: #ffffff;
        }
        .tasks-page-header h1 { margin: 0; font-family: 'Space Grotesk', sans-serif; font-size: 1.875rem; letter-spacing: -0.02em; }
        .tasks-page-header p { margin: 0.125rem 0 0; color: var(--muted-fg); font-size: 0.875rem; }

        .task-stats-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1.25rem; }
        .task-stat-card {
            --stat-color: var(--primary); display: flex; min-height: 158px; flex-direction: column; padding: 1.25rem;
            border: 1px solid var(--border-subtle); border-radius: 14px; background: var(--bg-card); box-shadow: 0 4px 20px -2px rgba(0,0,0,.5);
            transition: transform .25s ease, border-color .25s ease, box-shadow .25s ease;
        }
        .task-stat-card:hover { transform: translateY(-2px); border-color: rgba(255,42,47,.35); box-shadow: 0 12px 30px -8px rgba(0,0,0,.7), 0 0 20px -4px rgba(255,42,47,.18); }
        .task-stat-card--completed { --stat-color: #10b981; }
        .task-stat-card--pending { --stat-color: #f59e0b; }
        .task-stat-top { display: flex; align-items: center; justify-content: space-between; gap: .75rem; }
        .task-stat-label { display: inline-flex; align-items: center; gap: .5rem; color: #cbd5e1; font-size: .75rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; }
        .task-stat-dot { width: .5rem; height: .5rem; border-radius: 50%; background: var(--stat-color); box-shadow: 0 0 10px var(--stat-color); }
        .task-stat-icon { display: inline-flex; width: 2.25rem; height: 2.25rem; align-items: center; justify-content: center; border: 1px solid color-mix(in srgb,var(--stat-color) 35%,transparent); border-radius: 10px; background: color-mix(in srgb,var(--stat-color) 12%,transparent); color: var(--stat-color); }
        .task-stat-value { display: block; margin-top: auto; color: var(--stat-color); font-family: 'Space Grotesk', sans-serif; font-size: clamp(2.35rem,4vw,3rem); font-weight: 700; line-height: 1; text-shadow: 0 0 20px color-mix(in srgb,var(--stat-color) 30%,transparent); }
        .task-stat-caption { margin: .55rem 0 0; color: var(--muted-fg); font-size: .75rem; font-weight: 600; letter-spacing: .05em; text-transform: uppercase; }

        .section-header { display: flex; align-items: center; gap: .875rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-subtle); }
        .section-accent { width: 4px; height: 1.75rem; flex: 0 0 4px; border-radius: 99px; background: linear-gradient(180deg,#ff2a2f,#b81419); box-shadow: 0 0 12px rgba(255,42,47,.45); }
        .section-title { margin: 0; font-family: 'Space Grotesk',sans-serif; font-size: 1.25rem; letter-spacing: .04em; text-transform: uppercase; }
        .tasks-grid { display: grid; grid-template-columns: repeat(3,minmax(0,1fr)); gap: 1.25rem; align-items: stretch; }

        .task-card { display: flex; min-width: 0; min-height: 252px; height: 100%; flex-direction: column; padding: 1.25rem; border: 1px solid var(--border-subtle); border-radius: 14px; background: var(--bg-card); box-shadow: 0 4px 20px -2px rgba(0,0,0,.5); transition: transform .25s ease,border-color .25s ease,box-shadow .25s ease; }
        .task-card:hover { transform: translateY(-3px); border-color: rgba(255,42,47,.35); box-shadow: 0 12px 30px -8px rgba(0,0,0,.7),0 0 20px -4px rgba(255,42,47,.18); }
        .task-card--completed { opacity: .72; }
        .task-card-top { display: flex; align-items: center; justify-content: space-between; gap: .75rem; margin-bottom: 1rem; }
        .task-difficulty,.task-reward { display: inline-flex; align-items: center; gap: .375rem; border-radius: 999px; font-size: .7rem; font-weight: 700; letter-spacing: .05em; line-height: 1; white-space: nowrap; }
        .task-difficulty { padding: .45rem .625rem; border: 1px solid color-mix(in srgb,var(--difficulty-color) 40%,transparent); background: var(--difficulty-background); color: var(--difficulty-color); text-transform: uppercase; }
        .task-difficulty-dot { width: .4rem; height: .4rem; border-radius: 50%; background: currentColor; }
        .task-reward { padding: .42rem .625rem; border: 1px solid rgba(245,158,11,.3); background: rgba(245,158,11,.1); color: #f59e0b; }
        .task-reward img { width: 14px; height: 14px; object-fit: contain; }
        .task-title { margin: 0 0 .625rem; font-family: 'Space Grotesk',sans-serif; font-size: 1.1rem; font-weight: 700; line-height: 1.35; }
        .task-description { display: -webkit-box; min-height: 3.9em; margin: 0; overflow: hidden; color: var(--muted-fg); font-size: .8125rem; line-height: 1.6; -webkit-box-orient: vertical; -webkit-line-clamp: 3; }
        .task-card-footer { margin-top: auto; padding-top: .5rem; }
        .task-solve-button,.task-completed-state { display: flex; width: 100%; min-height: 2.75rem; align-items: center; justify-content: center; border-radius: 10px; font-size: .75rem; font-weight: 700; letter-spacing: .06em; text-align: center; text-transform: uppercase; }
        .task-solve-button { border: 1px solid rgba(255,255,255,.15); background: linear-gradient(135deg,#ff2a2f,#b81419); box-shadow: 0 4px 14px rgba(255,42,47,.3); color: #ffffff; cursor: pointer; transition: transform .2s ease,box-shadow .2s ease,filter .2s ease; }
        .task-solve-button:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,42,47,.5); filter: brightness(1.08); }
        .task-solve-button:active { transform: translateY(0); }
        .task-completed-label { display: inline-flex; align-items: center; gap: .5rem; margin-bottom: 1rem; color: #10b981; font-size: .75rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; }
        .task-completed-state { border: 1px solid rgba(16,185,129,.3); background: rgba(16,185,129,.1); color: #10b981; }
        .task-empty-state { display: flex; min-height: 220px; flex-direction: column; align-items: center; justify-content: center; gap: .75rem; border: 1px dashed var(--border-subtle); border-radius: 14px; background: rgba(16,21,31,.5); color: var(--muted-fg); text-align: center; }
        .task-empty-state i { color: var(--primary); font-size: 1.5rem; }
        .task-empty-state p { margin: 0; font-size: .875rem; }

        @media (max-width:1279px) { .tasks-grid { grid-template-columns: repeat(2,minmax(0,1fr)); } }
        @media (max-width:767px) { .task-stats-grid,.tasks-grid { grid-template-columns: 1fr; } .task-stat-card { min-height: 140px; } .section-title { font-size: 1.05rem; } }
    </style>
</head>
<body>
    <div class="desktop-container">
        <?php include 'sidebar.php'; ?>

        <main class="desktop-main tasks-page">
            <header class="card tasks-page-header">
                <div class="tasks-page-header__content">
                    <span class="tasks-page-header__icon"><i class="fas fa-list-check"></i></span>
                    <div>
                        <h1>Programming Tasks</h1>
                        <p>Complete challenges to earn Eagle Points</p>
                    </div>
                </div>
            </header>

            <section class="task-stats-grid" aria-label="Task statistics">
                <article class="task-stat-card">
                    <div class="task-stat-top"><span class="task-stat-label"><span class="task-stat-dot"></span>Total Tasks</span><span class="task-stat-icon"><i class="fas fa-list-check"></i></span></div>
                    <span class="task-stat-value"><?php echo $totalTaskCount; ?></span><p class="task-stat-caption">Available Tasks</p>
                </article>
                <article class="task-stat-card task-stat-card--completed">
                    <div class="task-stat-top"><span class="task-stat-label"><span class="task-stat-dot"></span>Completed</span><span class="task-stat-icon"><i class="fas fa-circle-check"></i></span></div>
                    <span class="task-stat-value"><?php echo $completedTaskCount; ?></span><p class="task-stat-caption">Tasks Solved</p>
                </article>
                <article class="task-stat-card task-stat-card--pending">
                    <div class="task-stat-top"><span class="task-stat-label"><span class="task-stat-dot"></span>Pending</span><span class="task-stat-icon"><i class="fas fa-hourglass-half"></i></span></div>
                    <span class="task-stat-value"><?php echo $pendingTaskCount; ?></span><p class="task-stat-caption">Pending Tasks</p>
                </article>
            </section>

            <section aria-labelledby="challenge-title">
                <div class="section-header"><span class="section-accent"></span><h2 id="challenge-title" class="section-title">LeetCode Programming Challenges</h2></div>
                <?php if ($databaseTasks): ?>
                    <div class="tasks-grid">
                        <?php foreach ($databaseTasks as $task): ?>
                            <?php
                            $difficultyColor = $task['difficulty'] === 'Hard' ? '#ef4444' : ($task['difficulty'] === 'Medium' ? '#f59e0b' : '#10b981');
                            $difficultyBackground = $task['difficulty'] === 'Hard' ? 'rgba(239,68,68,.15)' : ($task['difficulty'] === 'Medium' ? 'rgba(245,158,11,.15)' : 'rgba(16,185,129,.15)');
                            $safeTitle = htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8');
                            $safeDescription = htmlspecialchars($task['description'], ENT_QUOTES, 'UTF-8');
                            ?>
                            <article class="task-card <?php echo $task['completed'] ? 'task-card--completed' : ''; ?>">
                                <div>
                                    <div class="task-card-top">
                                        <span class="task-difficulty" style="--difficulty-color: <?php echo $difficultyColor; ?>; --difficulty-background: <?php echo $difficultyBackground; ?>;"><span class="task-difficulty-dot"></span><?php echo htmlspecialchars($task['difficulty'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span class="task-reward"><img src="images/coin.png" alt=""><span><?php echo $task['reward']; ?> EC</span></span>
                                    </div>
                                    <?php if ($task['completed']): ?><div class="task-completed-label"><i class="fas fa-circle-check"></i>Completed</div><?php endif; ?>
                                    <h3 class="task-title"><?php echo $safeTitle; ?></h3>
                                    <p class="task-description"><?php echo $safeDescription; ?></p>
                                </div>
                                <div class="task-card-footer">
                                    <?php if ($task['completed']): ?>
                                        <div class="task-completed-state"><i class="fas fa-check"></i>&nbsp; Mission Done</div>
                                    <?php else: ?>
                                        <button class="solve-btn task-solve-button" data-task="<?php echo htmlspecialchars(rawurlencode($task['title']), ENT_QUOTES, 'UTF-8'); ?>" data-task-id="<?php echo htmlspecialchars($task['id'], ENT_QUOTES, 'UTF-8'); ?>" data-coins="<?php echo $task['reward']; ?>">Solve Problem</button>
                                    <?php endif; ?>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="task-empty-state"><i class="fas fa-clipboard-list"></i><p>No programming tasks are available yet.</p></div>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <div id="langModalOverlay" style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;padding:24px;background:rgba(0,0,0,.85);backdrop-filter:blur(8px);">
        <div style="width:100%;max-width:700px;overflow:hidden;border:1px solid var(--primary);border-radius:12px;background:#0d1117;">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:24px;border-bottom:1px solid rgba(255,255,255,.08);background:linear-gradient(to right,rgba(255,42,47,.2),transparent);">
                <div><div style="margin-bottom:4px;color:var(--primary);font-size:.7rem;font-weight:800;letter-spacing:.15em;text-transform:uppercase;">Choose Language</div><h3 id="langModalTaskName" style="margin:0;color:#fff;font-size:1rem;font-weight:700;text-transform:uppercase;"></h3></div>
                <button type="button" onclick="closeLangModal()" aria-label="Close" style="border:0;background:none;color:#94a3b8;cursor:pointer;font-size:1.2rem;">&#10005;</button>
            </div>
            <div style="padding:28px;"><p style="margin:0 0 20px;color:#94a3b8;font-size:.8rem;letter-spacing:.1em;text-transform:uppercase;">Select the programming language you want to use:</p>
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;">
                    <button class="lang-choice-btn" onclick="selectLang('lab/codings/pythoni.php')"><i class="fab fa-python"></i><span>Python</span></button>
                    <button class="lang-choice-btn" onclick="selectLang('lab/codings/js.php')"><i class="fab fa-js-square"></i><span>JavaScript</span></button>
                    <button class="lang-choice-btn" onclick="selectLang('lab/codings/cpp.php')"><i class="fas fa-cogs"></i><span>C++</span></button>
                    <button class="lang-choice-btn" onclick="selectLang('lab/codings/php.php')"><i class="fab fa-php"></i><span>PHP</span></button>
                </div>
            </div>
        </div>
    </div>
    <style>
        .lang-choice-btn { display:flex;flex-direction:column;align-items:center;gap:10px;padding:18px 12px;border:1px solid rgba(255,255,255,.12);border-radius:10px;background:rgba(255,255,255,.03);color:#fff;cursor:pointer;font-size:.8rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;transition:transform .2s ease,border-color .2s ease,background .2s ease,box-shadow .2s ease; }
        .lang-choice-btn i { color:var(--primary);font-size:2.25rem; }
        .lang-choice-btn:hover { transform:translateY(-4px);border-color:var(--primary);background:rgba(255,42,47,.08);box-shadow:0 8px 24px rgba(255,42,47,.2); }
    </style>
    <script>
        function openLangModal(taskTitle, taskCoins, taskId) {
            document.getElementById('langModalTaskName').textContent = decodeURIComponent(taskTitle);
            window._pendingTask = taskTitle;
            window._pendingCoins = taskCoins;
            window._pendingTaskId = taskId;
            document.getElementById('langModalOverlay').style.display = 'flex';
        }
        function closeLangModal() { document.getElementById('langModalOverlay').style.display = 'none'; }
        function selectLang(labUrl) {
            localStorage.setItem('tasksinfo', [decodeURIComponent(window._pendingTask), window._pendingCoins, window._pendingTaskId]);
            closeLangModal();
            window.location.href = labUrl + '?id=' + encodeURIComponent(window._pendingTaskId);
        }
        document.getElementById('langModalOverlay').addEventListener('click', function (event) { if (event.target === this) closeLangModal(); });
        document.addEventListener('click', function (event) {
            const button = event.target.closest('.solve-btn');
            if (button) openLangModal(button.dataset.task, button.dataset.coins, button.dataset.taskId);
        });
        setInterval(function () { fetch('api/heartbeat.php').catch(function () {}); }, 60000);
    </script>
</body>
</html>
