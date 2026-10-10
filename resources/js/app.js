import { ScrollTrigger } from './lib/gsap';
import { initSmoothScroll, setScrollLocked } from './modules/smooth-scroll';
import { runPreloader } from './modules/preloader';
import { initHero } from './modules/hero';
import { initProcess } from './modules/process';
import { initMarquee } from './modules/marquee';
import { initReveals } from './modules/reveals';
import { initServicePreview } from './modules/services';
import { initHeader } from './modules/header';
import { initMenu } from './modules/menu';
import { initCursor } from './modules/cursor';
import { initMagnetic } from './modules/magnetic';
import { initGoToTop, initScrollCue } from './modules/go-to-top';
import { initEmbeds } from './modules/embeds';

// Registers the fonts with Vite's manifest so the layout can preload them via Vite::asset().
import.meta.glob(['../fonts/**']);

initSmoothScroll();
setScrollLocked(true);

// Pinned sections first, top to bottom, so every later trigger measures with their spacers in place.
const hero = initHero();
initProcess();

initMarquee();
initReveals();
initServicePreview();
initHeader();
initMenu();
initCursor();
initMagnetic();
initGoToTop();
initScrollCue();
initEmbeds();

runPreloader(() => hero.playIntro()).then(() => {
    setScrollLocked(false);
    ScrollTrigger.refresh();
});

// Late layout changes (font swaps, SplitText re-splits, images) shift trigger positions;
// re-measure whenever the page's content height actually changes.
const content = document.getElementById('smooth-content');
let measuredHeight = content.offsetHeight;
let refreshTimer;

new ResizeObserver(() => {
    clearTimeout(refreshTimer);
    refreshTimer = setTimeout(() => {
        if (Math.abs(content.offsetHeight - measuredHeight) > 1) {
            measuredHeight = content.offsetHeight;
            ScrollTrigger.refresh();
        }
    }, 200);
}).observe(content);
