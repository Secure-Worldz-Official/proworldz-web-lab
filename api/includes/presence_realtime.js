(function () {
    if (window.__presenceRealtimeInitialized) {
        return;
    }
    window.__presenceRealtimeInitialized = true;

    var HEARTBEAT_URL = 'api/heartbeat.php';
    var OFFLINE_URL = 'api/set_offline.php';
    var ONLINE_STATUS_URL = 'api/online_status.php';

    var ACTIVE_INTERVAL_MS = 25000;
    var HIDDEN_INTERVAL_MS = 55000;
    var pollingHandle = null;
    var onlineStatusHandle = null;

    function postHeartbeat() {
        return fetch(HEARTBEAT_URL, {
            method: 'POST',
            credentials: 'same-origin',
            cache: 'no-store',
            keepalive: true,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            body: ''
        }).catch(function () {
            return null;
        });
    }

    function clearPresenceTimer() {
        if (pollingHandle !== null) {
            clearInterval(pollingHandle);
            pollingHandle = null;
        }
    }

    function startPresenceTimer() {
        clearPresenceTimer();
        var interval = document.visibilityState === 'visible' ? ACTIVE_INTERVAL_MS : HIDDEN_INTERVAL_MS;
        pollingHandle = setInterval(postHeartbeat, interval);
    }

    function sendOfflineNow() {
        try {
            if (navigator.sendBeacon) {
                var body = new Blob([''], { type: 'application/x-www-form-urlencoded; charset=UTF-8' });
                navigator.sendBeacon(OFFLINE_URL, body);
                return;
            }
        } catch (e) {
        }

        fetch(OFFLINE_URL, {
            method: 'POST',
            credentials: 'same-origin',
            cache: 'no-store',
            keepalive: true,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            body: ''
        }).catch(function () {
            return null;
        });
    }

    function fetchOnlineUserIds() {
        return fetch(ONLINE_STATUS_URL, {
            method: 'GET',
            credentials: 'same-origin',
            cache: 'no-store'
        }).then(function (response) {
            if (!response.ok) {
                throw new Error('Failed to fetch online status');
            }
            return response.json();
        });
    }

    function startOnlineStatusPolling(onUpdate, intervalMs) {
        var safeInterval = Math.max(5000, Number(intervalMs) || 12000);

        function tick() {
            fetchOnlineUserIds()
                .then(function (ids) {
                    if (!Array.isArray(ids) || typeof onUpdate !== 'function') {
                        return;
                    }
                    var normalized = new Set(ids.map(function (id) { return String(id); }));
                    onUpdate(normalized);
                })
                .catch(function () {
                    return null;
                });
        }

        tick();

        if (onlineStatusHandle !== null) {
            clearInterval(onlineStatusHandle);
        }
        onlineStatusHandle = setInterval(tick, safeInterval);

        return function stop() {
            if (onlineStatusHandle !== null) {
                clearInterval(onlineStatusHandle);
                onlineStatusHandle = null;
            }
        };
    }

    window.presenceRealtime = {
        heartbeatNow: postHeartbeat,
        fetchOnlineUserIds: fetchOnlineUserIds,
        startOnlineStatusPolling: startOnlineStatusPolling
    };

    postHeartbeat();
    startPresenceTimer();

    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'visible') {
            postHeartbeat();
        }
        startPresenceTimer();
    });

    window.addEventListener('focus', postHeartbeat);
    window.addEventListener('online', postHeartbeat);
    window.addEventListener('pagehide', sendOfflineNow);
    window.addEventListener('beforeunload', sendOfflineNow);
})();
