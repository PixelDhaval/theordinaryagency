import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { ScrollSmoother } from 'gsap/ScrollSmoother';
import { SplitText } from 'gsap/SplitText';

gsap.registerPlugin(ScrollTrigger, ScrollSmoother, SplitText);

gsap.defaults({ ease: 'expo.out', duration: 1 });

ScrollTrigger.config({ ignoreMobileResize: true });

if (import.meta.env.DEV) {
    window.gsap = gsap;
    window.ScrollTrigger = ScrollTrigger;
}

export const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

export const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

export { gsap, ScrollTrigger, ScrollSmoother, SplitText };
