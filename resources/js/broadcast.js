const HLS_SCRIPT = 'https://cdn.jsdelivr.net/npm/hls.js@1/dist/hls.min.js';
const POLL_INTERVAL = 10000;

const loadHls = () => new Promise((resolve, reject) => {
    if (window.Hls) {
        resolve(window.Hls);

        return;
    }

    const script = document.createElement('script');
    script.src = HLS_SCRIPT;
    script.onload = () => resolve(window.Hls);
    script.onerror = reject;
    document.head.append(script);
});

const attachStream = async (media) => {
    const source = media.dataset.hls;

    if (media.canPlayType('application/vnd.apple.mpegurl')) {
        media.src = source;

        return;
    }

    try {
        const Hls = await loadHls();

        if (Hls.isSupported()) {
            const hls = new Hls({ lowLatencyMode: true });
            hls.loadSource(source);
            hls.attachMedia(media);
        }
    } catch {
        media.insertAdjacentText('afterend', 'This stream could not be loaded in your browser.');
    }
};

const watchStatus = (page) => {
    setInterval(async () => {
        if (document.hidden) {
            return;
        }

        try {
            const response = await fetch(page.dataset.statusUrl, { headers: { Accept: 'application/json' } });
            const { status } = await response.json();

            if (status !== page.dataset.status) {
                window.location.reload();
            }
        } catch {
            // Try again on the next tick.
        }
    }, POLL_INTERVAL);
};

export const initBroadcasts = () => {
    document.querySelectorAll('[data-hls]').forEach(attachStream);

    const page = document.querySelector('[data-broadcast]');

    if (page && page.dataset.status !== 'ended') {
        watchStatus(page);
    }
};
