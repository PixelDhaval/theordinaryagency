import { gsap, ScrollTrigger, reduceMotion } from '../lib/gsap';

/** Infinite marquee that speeds up with scroll velocity and follows scroll direction. */
export function initMarquee() {
    const track = document.querySelector('[data-marquee]');

    if (!track || reduceMotion) {
        return;
    }

    const loop = gsap.to(track, { xPercent: -50, duration: 40, ease: 'none', repeat: -1 });
    // Start deep into the repeat cycle so reverse playback never hits time 0.
    loop.totalTime(loop.duration() * 1000);

    let settle;

    ScrollTrigger.create({
        trigger: track,
        start: 'top bottom',
        end: 'bottom top',
        onUpdate: (self) => {
            const boost = Math.min(Math.abs(self.getVelocity()) / 250, 6);

            settle?.kill();
            loop.timeScale(self.direction * (1 + boost));
            settle = gsap.to(loop, { timeScale: self.direction, duration: 1.2, ease: 'power2.out' });
        },
    });
}
