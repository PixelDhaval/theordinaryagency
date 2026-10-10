import { gsap, reduceMotion } from '../lib/gsap';
import { setScrollLocked } from './smooth-scroll';
import { lockHeader } from './header';

/** Full-screen navigation overlay with a clip-path wipe and staggered link reveal. */
export function initMenu() {
    const toggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('site-menu');

    if (!toggle || !menu) {
        return;
    }

    const items = menu.querySelectorAll('[data-menu-item]');
    const foot = menu.querySelector('[data-menu-foot]');
    const lineTop = toggle.querySelector('[data-menu-line="1"]');
    const lineBottom = toggle.querySelector('[data-menu-line="2"]');
    const labels = toggle.querySelectorAll('[data-menu-label], [data-menu-label-alt]');
    const previewImages = gsap.utils.toArray(menu.querySelectorAll('[data-menu-preview-image]'));
    let isOpen = false;

    const timeline = gsap
        .timeline({
            paused: true,
            defaults: { ease: 'expo.inOut' },
            onReverseComplete: () => gsap.set(menu, { visibility: 'hidden' }),
        })
        .set(menu, { visibility: 'visible' })
        .fromTo(menu, { clipPath: 'inset(0% 0% 100% 0%)' }, { clipPath: 'inset(0% 0% 0% 0%)', duration: 1 })
        .to(lineTop, { y: 4, rotation: 45, duration: 0.6 }, 0)
        .to(lineBottom, { y: -4, rotation: -45, duration: 0.6 }, 0)
        .fromTo(items, { yPercent: 110 }, { yPercent: 0, duration: 1, stagger: 0.05, ease: 'expo.out' }, 0.4)
        .fromTo(foot, { autoAlpha: 0, y: 20 }, { autoAlpha: 1, y: 0, duration: 0.8, ease: 'expo.out' }, 0.65);

    if (reduceMotion) {
        timeline.timeScale(20);
    }

    const showPreview = (key) => {
        previewImages.forEach((image) => {
            const active = image.dataset.menuPreviewImage === key;
            gsap.to(image, { opacity: active ? 1 : 0, scale: active ? 1 : 1.1, duration: 0.8, ease: 'expo.out', overwrite: 'auto' });
        });
    };

    const setOpen = (open) => {
        isOpen = open;
        toggle.setAttribute('aria-expanded', String(open));
        menu.setAttribute('aria-hidden', String(!open));
        labels.forEach((label) => (label.textContent = open ? 'Close' : 'Menu'));
        setScrollLocked(open);
        lockHeader(open);

        if (open) {
            timeline.timeScale(reduceMotion ? 20 : 1).play();
            showPreview(items[0]?.dataset.menuImage);
            items[0]?.focus({ preventScroll: true });
        } else {
            timeline.timeScale(reduceMotion ? 20 : 1.4).reverse();
            toggle.focus({ preventScroll: true });
        }
    };

    toggle.addEventListener('click', () => setOpen(!isOpen));

    items.forEach((item) => {
        item.addEventListener('mouseenter', () => showPreview(item.dataset.menuImage));
        item.addEventListener('focus', () => showPreview(item.dataset.menuImage));
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isOpen) {
            setOpen(false);
        }
    });
}
