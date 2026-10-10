import { ScrollTrigger, reduceMotion } from '../lib/gsap';

const MAX_EMBED_HEIGHT = 20000;

/**
 * Sizes sandboxed custom-HTML frames to their content. The frames have an opaque origin,
 * so messages are matched by their source window rather than by origin.
 */
function initEmbedResizing() {
    const frames = [...document.querySelectorAll('iframe[data-embed-frame]')];

    if (!frames.length) {
        return;
    }

    window.addEventListener('message', (event) => {
        const frame = frames.find((candidate) => candidate.contentWindow === event.source);
        const height = Number(event.data?.height);

        if (!frame || event.data?.type !== 'tod-embed:height' || !Number.isFinite(height) || height <= 0) {
            return;
        }

        frame.style.height = `${Math.min(Math.ceil(height), MAX_EMBED_HEIGHT)}px`;
        ScrollTrigger.refresh();
    });
}

/** Autoplaying case-study loops stay paused for visitors who prefer reduced motion. */
function initAutoplayVideos() {
    if (!reduceMotion) {
        return;
    }

    document.querySelectorAll('video[data-autoplay-video]').forEach((video) => {
        video.removeAttribute('autoplay');
        video.pause();
        video.setAttribute('controls', '');
        video.removeAttribute('aria-hidden');
    });
}

export function initEmbeds() {
    initEmbedResizing();
    initAutoplayVideos();
}
