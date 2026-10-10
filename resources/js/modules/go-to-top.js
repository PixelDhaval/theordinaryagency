import { gsap, ScrollTrigger } from '../lib/gsap';
import { scrollToTarget } from './smooth-scroll';

/** Go-to-top button with a scroll-progress ring; any [data-scroll-top] element scrolls home. */
export function initGoToTop() {
    const button = document.getElementById('go-to-top');
    const ring = button?.querySelector('[data-progress-ring]');
    let visible = false;

    document.querySelectorAll('[data-scroll-top]').forEach((trigger) => {
        trigger.addEventListener('click', () => scrollToTarget(0));
    });

    if (!button) {
        return;
    }

    ScrollTrigger.create({
        start: 0,
        end: 'max',
        onUpdate: (self) => {
            gsap.set(ring, { strokeDashoffset: 1 - self.progress });

            const shouldShow = self.scroll() > window.innerHeight;

            if (shouldShow !== visible) {
                visible = shouldShow;
                gsap.to(button, { autoAlpha: visible ? 1 : 0, scale: visible ? 1 : 0.6, duration: 0.6, ease: 'expo.out', overwrite: 'auto' });
            }
        },
    });
}

export function initScrollCue() {
    document.querySelectorAll('[data-scroll-cue]').forEach((cue) => {
        cue.addEventListener('click', () => scrollToTarget('#philosophy', 'top 10%'));
    });
}
