import { gsap, finePointer, reduceMotion } from '../lib/gsap';

/** Floating image preview that follows the cursor over the service list (desktop only). */
export function initServicePreview() {
    const section = document.querySelector('[data-services]');

    if (!section || !finePointer || reduceMotion) {
        return;
    }

    const preview = section.querySelector('[data-service-preview]');
    const list = section.querySelector('[data-service-list]');
    const images = gsap.utils.toArray(preview.querySelectorAll('[data-preview-image]'));

    gsap.set(preview, { xPercent: -50, yPercent: -50, scale: 0.85 });

    const moveX = gsap.quickTo(preview, 'x', { duration: 0.7, ease: 'power3.out' });
    const moveY = gsap.quickTo(preview, 'y', { duration: 0.7, ease: 'power3.out' });
    const tilt = gsap.quickTo(preview, 'rotation', { duration: 0.9, ease: 'power3.out' });
    let lastX = null;
    let levelOut;

    section.addEventListener('mousemove', (event) => {
        const bounds = section.getBoundingClientRect();
        const x = event.clientX - bounds.left;

        moveX(x);
        moveY(event.clientY - bounds.top);

        if (lastX !== null) {
            tilt(gsap.utils.clamp(-6, 6, (x - lastX) * 0.4));
        }

        lastX = x;
        clearTimeout(levelOut);
        levelOut = setTimeout(() => tilt(0), 120);
    });

    section.addEventListener('mouseleave', () => {
        lastX = null;
    });

    section.querySelectorAll('[data-service-link]').forEach((link) => {
        link.addEventListener('mouseenter', () => {
            const key = link.dataset.serviceImage;

            images.forEach((image) => {
                gsap.to(image, {
                    autoAlpha: image.dataset.previewImage === key ? 1 : 0,
                    duration: 0.5,
                    ease: 'power2.out',
                    overwrite: 'auto',
                });
            });
        });
    });

    list.addEventListener('mouseenter', () => gsap.to(preview, { autoAlpha: 1, scale: 1, duration: 0.6, overwrite: 'auto' }));
    list.addEventListener('mouseleave', () => {
        gsap.to(preview, { autoAlpha: 0, scale: 0.85, duration: 0.5, ease: 'power3.out', overwrite: 'auto' });
        tilt(0);
    });
}
