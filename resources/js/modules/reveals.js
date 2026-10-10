import { gsap, SplitText, reduceMotion } from '../lib/gsap';

const once = (trigger, start = 'top 88%') => ({ trigger, start, once: true });

/** Masked line-by-line headline reveals. Re-splits on resize/font load via autoSplit. */
function initSplitLines() {
    document.querySelectorAll('[data-split-lines]').forEach((element) => {
        SplitText.create(element, {
            type: 'lines',
            mask: 'lines',
            autoSplit: true,
            onSplit: (self) =>
                gsap.from(self.lines, {
                    yPercent: 115,
                    duration: 1.4,
                    stagger: 0.09,
                    scrollTrigger: once(element),
                }),
        });
    });
}

function initFadeReveals() {
    document.querySelectorAll('[data-reveal]').forEach((element) => {
        gsap.from(element, { autoAlpha: 0, y: 28, duration: 1.2, scrollTrigger: once(element, 'top 92%') });
    });
}

/** Image wipes up from the bottom while the image itself settles from a zoom. */
function initClipReveals() {
    document.querySelectorAll('[data-clip-reveal]').forEach((element) => {
        const image = element.querySelector('img');

        gsap.timeline({ scrollTrigger: once(element, 'top 85%') })
            .fromTo(
                element,
                { clipPath: 'inset(100% 0% 0% 0%)' },
                { clipPath: 'inset(0% 0% 0% 0%)', duration: 1.6, ease: 'expo.inOut' },
            )
            .from(image, { scale: 1.35, duration: 2 }, 0.1);
    });
}

/** Paragraph words brighten one by one as the reader scrolls through it. */
function initScrubWords() {
    document.querySelectorAll('[data-scrub-words]').forEach((element) => {
        const split = SplitText.create(element, { type: 'words' });

        gsap.fromTo(
            split.words,
            { opacity: 0.14 },
            {
                opacity: 1,
                stagger: 0.4,
                ease: 'none',
                scrollTrigger: { trigger: element, start: 'top 80%', end: 'bottom 55%', scrub: true },
            },
        );
    });
}

function initParallax() {
    document.querySelectorAll('[data-parallax]').forEach((image) => {
        gsap.fromTo(
            image,
            { yPercent: -7 },
            {
                yPercent: 7,
                ease: 'none',
                scrollTrigger: { trigger: image.parentElement, start: 'top bottom', end: 'bottom top', scrub: true },
            },
        );
    });
}

/** Offset cards drift at a different speed than their neighbours (desktop only). */
function initDrift() {
    const elements = document.querySelectorAll('[data-drift]');

    if (!elements.length) {
        return;
    }

    gsap.matchMedia().add('(min-width: 768px)', () => {
        elements.forEach((element) => {
            gsap.fromTo(
                element,
                { y: 80 },
                {
                    y: -80,
                    ease: 'none',
                    scrollTrigger: { trigger: element, start: 'top bottom', end: 'bottom top', scrub: true },
                },
            );
        });
    });
}

function initRowLines() {
    document.querySelectorAll('[data-service-list]').forEach((list) => {
        gsap.from(list.querySelectorAll('[data-row-line]'), {
            scaleX: 0,
            duration: 1.6,
            stagger: 0.08,
            ease: 'expo.inOut',
            scrollTrigger: once(list, 'top 85%'),
        });
    });
}

function initFooterWordmark() {
    const wordmark = document.querySelector('[data-footer-wordmark]');

    if (!wordmark) {
        return;
    }

    gsap.from(wordmark, {
        yPercent: 100,
        duration: 1.6,
        scrollTrigger: once(wordmark.parentElement, 'top 95%'),
    });
}

export function initReveals() {
    if (reduceMotion) {
        return;
    }

    initSplitLines();
    initFadeReveals();
    initClipReveals();
    initScrubWords();
    initParallax();
    initDrift();
    initRowLines();
    initFooterWordmark();
}
