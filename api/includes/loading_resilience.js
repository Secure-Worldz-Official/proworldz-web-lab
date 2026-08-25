(function () {
    'use strict';

    var overlay = null;
    var revealed = false;
    var reportEndpoint = 'api/client_error_log.php';

    function report(type, message, detail) {
        var payload = {
            type: type,
            message: String(message || 'Unknown client-side error').slice(0, 500),
            detail: String(detail || '').slice(0, 1000),
            path: window.location.pathname,
            timestamp: new Date().toISOString()
        };

        // Keep the report best-effort: diagnostics must never affect page loading.
        try {
            if (navigator.sendBeacon) {
                navigator.sendBeacon(reportEndpoint, new Blob([JSON.stringify(payload)], { type: 'application/json' }));
                return;
            }

            fetch(reportEndpoint, {
                method: 'POST',
                credentials: 'same-origin',
                cache: 'no-store',
                keepalive: true,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            }).catch(function () {});
        } catch (ignore) {}
    }

    function reveal(reason, shouldReport) {
        if (revealed) return;
        revealed = true;
        document.body.classList.add('loaded');
        if (reason && shouldReport) report('loading_fallback', reason);
    }

    function initialise() {
        overlay = document.getElementById('loader-wrapper');
        if (!overlay) return;

        // Do not wait for remote fonts, images, or analytics. They can be slow or blocked.
        window.setTimeout(reveal, 100);

        // Retain a safety net for pages that are changed to defer their DOM work later.
        window.setTimeout(function () {
            if (!revealed) {
                var text = document.getElementById('loader-text');
                if (text) text.textContent = 'CONTENT IS TAKING LONGER THAN EXPECTED…';
                reveal('loader_timeout', true);
            }
        }, 8000);
    }

    window.addEventListener('error', function (event) {
        report('window_error', event.message, event.filename + ':' + event.lineno + ':' + event.colno);
        // A script error must not leave a loading overlay over usable server-rendered content.
        if (overlay) reveal();
    });

    window.addEventListener('unhandledrejection', function (event) {
        var reason = event.reason && event.reason.message ? event.reason.message : event.reason;
        report('unhandled_rejection', reason);
        if (overlay) reveal();
    });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialise, { once: true });
    } else {
        initialise();
    }
})();
