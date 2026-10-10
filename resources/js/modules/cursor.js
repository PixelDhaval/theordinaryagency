import { gsap, finePointer, reduceMotion } from '../lib/gsap';

const BASE_SCALE = 0.14;
const HOVER_SCALE = 0.45;

/** Blend-mode cursor that grows over interactive elements and shows a label for [data-cursor]. */
export function initCursor() {
    const cursor = document.querySelector('.cursor');

    if (!cursor || !finePointer || reduceMotion) {
        return;
    }

    document.documentElement.classList.add('has-cursor');

    const ring = cursor.querySelector('[data-cursor-ring]');
    const label = cursor.querySelector('[data-cursor-label]');

    gsap.set(cursor, { xPercent: -50, yPercent: -50, autoAlpha: 0 });
    gsap.set(ring, { scale: BASE_SCALE });

    const moveX = gsap.quickTo(cursor, 'x', { duration: 0.45, ease: 'power3.out' });
    const moveY = gsap.quickTo(cursor, 'y', { duration: 0.45, ease: 'power3.out' });

    window.addEventListener('mousemove', (event) => {
        moveX(event.clientX);
        moveY(event.clientY);
        gsap.to(cursor, { autoAlpha: 1, duration: 0.3, overwrite: 'auto' });
    });

    document.addEventListener('mouseleave', () => gsap.to(cursor, { autoAlpha: 0, duration: 0.3 }));

    document.addEventListener('mouseover', (event) => {
        const target = event.target.closest('[data-cursor], a, button');

        if (!target) {
            return;
        }

        const text = target.dataset.cursor ?? '';
        label.textContent = text;

        gsap.to(ring, { scale: text ? 1 : HOVER_SCALE, duration: 0.6, ease: 'expo.out', overwrite: 'auto' });
        gsap.to(label, { opacity: text ? 1 : 0, duration: 0.3, overwrite: 'auto' });
    });

    document.addEventListener('mouseout', (event) => {
        const target = event.target.closest('[data-cursor], a, button');

        if (!target || target.contains(event.relatedTarget)) {
            return;
        }

        gsap.to(ring, { scale: BASE_SCALE, duration: 0.6, ease: 'expo.out', overwrite: 'auto' });
        gsap.to(label, { opacity: 0, duration: 0.2, overwrite: 'auto' });
    });
}
