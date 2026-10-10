import { gsap, reduceMotion } from '../lib/gsap';

/**
 * Hero: the ring image opens from a card to full-bleed as the section is scrolled (pinned + scrubbed),
 * while the headline lines drift apart. The intro animates separate inner elements so the two never
 * compete for the same properties.
 */
export function initHero() {
    const hero = document.querySelector('[data-hero]');

    if (!hero || reduceMotion) {
        return { playIntro: () => {} };
    }

    const media = hero.querySelector('[data-hero-media]');
    const mediaInner = hero.querySelector('[data-hero-media-inner]');
    const image = hero.querySelector('[data-hero-image]');
    const lines = gsap.utils.toArray(hero.querySelectorAll('[data-hero-line]'));
    const masks = lines.map((line) => line.parentElement);
    const meta = hero.querySelector('[data-hero-meta]');
    const foot = hero.querySelector('[data-hero-foot]');
    const caption = hero.querySelector('[data-hero-caption]');
    const introItems = [...meta.children, ...foot.children];

    // Browsers normalise computed inset() to its shortest form, which GSAP would mis-map,
    // so start/end values are always given explicitly with all four sides.
    const cardInset = () =>
        window.matchMedia('(min-width: 768px)').matches ? 'inset(30% 34% 24% 34%)' : 'inset(34% 12% 30% 12%)';

    gsap.set(image, { scale: 1.25 });
    gsap.set(caption, { y: 40 });

    gsap.timeline({
        defaults: { ease: 'none' },
        scrollTrigger: {
            trigger: hero,
            start: 'top top',
            end: '+=110%',
            pin: true,
            scrub: 1,
            invalidateOnRefresh: true,
        },
    })
        .fromTo(media, { clipPath: cardInset }, { clipPath: 'inset(0% 0% 0% 0%)' }, 0)
        .to(image, { scale: 1 }, 0)
        .to(masks[0], { xPercent: -16, autoAlpha: 0 }, 0)
        .to(masks[1], { xPercent: 16, autoAlpha: 0 }, 0)
        .to(masks[2], { xPercent: -16, autoAlpha: 0 }, 0)
        .to([meta, foot], { autoAlpha: 0, y: -24 }, 0)
        .to(caption, { autoAlpha: 1, y: 0, ease: 'power2.out' }, 0.5);

    gsap.set(lines, { yPercent: 115 });
    gsap.set(mediaInner, { clipPath: 'inset(100% 0% 0% 0%)', scale: 1.2 });
    gsap.set(introItems, { autoAlpha: 0, y: 20 });

    const intro = gsap
        .timeline({ paused: true })
        .fromTo(
            mediaInner,
            { clipPath: 'inset(100% 0% 0% 0%)' },
            { clipPath: 'inset(0% 0% 0% 0%)', duration: 1.6, ease: 'expo.inOut' },
            0,
        )
        .to(mediaInner, { scale: 1, duration: 2.2 }, 0.2)
        .to(lines, { yPercent: 0, duration: 1.5, stagger: 0.1 }, 0.35)
        .to(introItems, { autoAlpha: 1, y: 0, duration: 1.2, stagger: 0.07 }, 0.9);

    return { playIntro: () => intro.play() };
}
