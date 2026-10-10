import { gsap, reduceMotion } from '../lib/gsap';

const html = document.documentElement;

const pageLoaded = () =>
    new Promise((resolve) => {
        if (document.readyState === 'complete') {
            resolve();
            return;
        }

        window.addEventListener('load', resolve, { once: true });
    });

const delay = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

/**
 * Counts up while the page loads, then lifts the curtain.
 * `onReveal` fires as the curtain starts moving so the hero intro overlaps it.
 */
export function runPreloader(onReveal) {
    const preloader = document.getElementById('preloader');

    const finish = () => {
        preloader?.remove();
        html.classList.remove('is-loading');
    };

    if (!preloader || reduceMotion) {
        finish();
        onReveal();
        return Promise.resolve();
    }

    const count = preloader.querySelector('[data-preloader-count]');
    const bar = preloader.querySelector('[data-preloader-bar]');
    const logo = preloader.querySelector('[data-preloader-logo]');
    const fades = preloader.querySelectorAll('[data-preloader-fade]');
    const progress = { value: 0 };

    const render = () => {
        count.textContent = String(Math.round(progress.value));
        gsap.set(bar, { scaleX: progress.value / 100 });
    };

    const intro = gsap
        .timeline()
        .from(logo, { scale: 0.4, rotation: -120, autoAlpha: 0, duration: 1.4 }, 0)
        .from(fades, { autoAlpha: 0, y: 12, stagger: 0.08, duration: 1 }, 0.15)
        .from(count, { yPercent: 100, duration: 1 }, 0.1)
        .to(progress, { value: 84, duration: 1.6, ease: 'power2.inOut', onUpdate: render }, 0);

    return Promise.all([intro.then(), Promise.race([pageLoaded(), delay(4000)])]).then(
        () =>
            new Promise((resolve) => {
                gsap.timeline({
                    onComplete: () => {
                        finish();
                        resolve();
                    },
                })
                    .to(progress, { value: 100, duration: 0.6, ease: 'power2.out', onUpdate: render })
                    .to(logo, { rotation: 180, scale: 0.6, autoAlpha: 0, duration: 0.7, ease: 'expo.in' }, '-=0.1')
                    .to(count, { yPercent: -100, duration: 0.7, ease: 'expo.in' }, '<')
                    .to(fades, { autoAlpha: 0, duration: 0.4 }, '<')
                    .to(preloader, { yPercent: -100, duration: 1.2, ease: 'expo.inOut', onStart: onReveal }, '-=0.15');
            }),
    );
}
