import { gsap, reduceMotion } from '../lib/gsap';

/** Pins the process section on tablet/desktop and scrolls its steps horizontally. */
export function initProcess() {
    const section = document.querySelector('[data-process]');

    if (!section || reduceMotion) {
        return;
    }

    const track = section.querySelector('[data-process-track]');
    const progress = section.querySelector('[data-process-progress]');
    const steps = gsap.utils.toArray(section.querySelectorAll('[data-process-step]'));

    gsap.matchMedia().add('(min-width: 768px)', () => {
        const distance = () => track.scrollWidth - window.innerWidth;

        const horizontal = gsap.to(track, {
            x: () => -distance(),
            ease: 'none',
            scrollTrigger: {
                trigger: section,
                start: 'top top',
                end: () => `+=${distance()}`,
                pin: true,
                scrub: 1,
                invalidateOnRefresh: true,
                onUpdate: (self) => gsap.set(progress, { scaleX: self.progress }),
            },
        });

        steps.forEach((step) => {
            gsap.from(step.children, {
                yPercent: 40,
                autoAlpha: 0,
                stagger: 0.1,
                duration: 1.2,
                scrollTrigger: {
                    trigger: step,
                    containerAnimation: horizontal,
                    start: 'left 85%',
                    toggleActions: 'play none none reverse',
                },
            });
        });
    });
}
