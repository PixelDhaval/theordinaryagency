import { gsap, finePointer, reduceMotion } from '../lib/gsap';

/** Elements marked [data-magnetic] lean toward the cursor and spring back on leave. */
export function initMagnetic() {
    if (!finePointer || reduceMotion) {
        return;
    }

    document.querySelectorAll('[data-magnetic]').forEach((element) => {
        const strength = Number(element.dataset.magnetic) || 0.35;
        const moveX = gsap.quickTo(element, 'x', { duration: 0.6, ease: 'power3.out' });
        const moveY = gsap.quickTo(element, 'y', { duration: 0.6, ease: 'power3.out' });

        element.addEventListener('mousemove', (event) => {
            const bounds = element.getBoundingClientRect();
            moveX((event.clientX - bounds.left - bounds.width / 2) * strength);
            moveY((event.clientY - bounds.top - bounds.height / 2) * strength);
        });

        element.addEventListener('mouseleave', () => {
            gsap.to(element, { x: 0, y: 0, duration: 1, ease: 'elastic.out(1, 0.4)', overwrite: 'auto' });
        });
    });
}
