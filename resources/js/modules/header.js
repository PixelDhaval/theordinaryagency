import { gsap, ScrollTrigger, reduceMotion } from '../lib/gsap';

let hidden = false;
let locked = false;

/** Keeps the header visible while the menu is open. */
export function lockHeader(state) {
    locked = state;

    if (state && hidden) {
        hidden = false;
        gsap.to('#site-header', { yPercent: 0, duration: 0.6, overwrite: 'auto' });
    }
}

/** Hides the header while scrolling down, reveals it on the way back up. */
export function initHeader() {
    const header = document.getElementById('site-header');

    if (!header || reduceMotion) {
        return;
    }

    ScrollTrigger.create({
        start: 0,
        end: 'max',
        onUpdate: (self) => {
            if (locked) {
                return;
            }

            const shouldHide = self.direction === 1 && self.scroll() > 160;

            if (shouldHide !== hidden) {
                hidden = shouldHide;
                gsap.to(header, { yPercent: hidden ? -120 : 0, duration: 0.7, ease: 'expo.out', overwrite: 'auto' });
            }
        },
    });
}
