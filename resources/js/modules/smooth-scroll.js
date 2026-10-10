import { ScrollSmoother, reduceMotion } from '../lib/gsap';

let smoother = null;

export function initSmoothScroll() {
    if (reduceMotion) {
        return null;
    }

    smoother = ScrollSmoother.create({
        wrapper: '#smooth-wrapper',
        content: '#smooth-content',
        smooth: 1.1,
        smoothTouch: false,
        effects: false,
    });

    return smoother;
}

export function getSmoother() {
    return smoother;
}

/** Scrolls to a target (element, selector or pixel value) through the smoother when present. */
export function scrollToTarget(target, position = 'top top') {
    if (smoother) {
        smoother.scrollTo(target, true, position);
        return;
    }

    const behavior = reduceMotion ? 'auto' : 'smooth';

    if (typeof target === 'number') {
        window.scrollTo({ top: target, behavior });
        return;
    }

    const element = typeof target === 'string' ? document.querySelector(target) : target;
    element?.scrollIntoView({ behavior });
}

export function setScrollLocked(locked) {
    smoother?.paused(locked);
    document.documentElement.style.overflow = locked ? 'hidden' : '';
}
