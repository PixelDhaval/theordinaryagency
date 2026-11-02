import { gsap, Flip, ScrollTrigger, reduceMotion } from '../lib/gsap';
import { setScrollLocked } from './smooth-scroll';

const pad = (number) => String(number).padStart(2, '0');

const isShown = (item) => item.style.display !== 'none';

/** Mark-type filter (Flip-animated) and the colour / mono switch for the index grid. */
function initIndex(root) {
    const grid = root.querySelector('[data-logo-grid]');

    if (!grid) {
        return;
    }

    const items = [...grid.querySelectorAll('[data-logo-item]')];
    const filters = root.querySelectorAll('[data-logo-filter]');
    const views = root.querySelectorAll('[data-logo-view]');

    filters.forEach((button) => {
        button.addEventListener('click', () => {
            const type = button.dataset.logoFilter;
            const state = Flip.getState(items);

            filters.forEach((filter) => filter.setAttribute('aria-pressed', String(filter === button)));
            // Inline display rather than [hidden], which Tailwind forces with !important and Flip can't lift for leaving items.
            items.forEach((item) => {
                item.style.display = type === 'all' || item.dataset.type === type ? '' : 'none';
            });

            if (reduceMotion) {
                return;
            }

            Flip.from(state, {
                duration: 0.9,
                ease: 'expo.inOut',
                absolute: true,
                scale: true,
                onEnter: (elements) => gsap.fromTo(elements, { autoAlpha: 0, scale: 0.92 }, { autoAlpha: 1, scale: 1, duration: 0.8, delay: 0.3 }),
                onLeave: (elements) => gsap.to(elements, { autoAlpha: 0, scale: 0.92, duration: 0.5, ease: 'power2.in' }),
            });
        });
    });

    views.forEach((button) => {
        button.addEventListener('click', () => {
            views.forEach((view) => view.setAttribute('aria-pressed', String(view === button)));
            grid.classList.toggle('is-mono', button.dataset.logoView === 'mono');
        });
    });
}

/** Full-screen viewer over the currently filtered marks, with a light / dark / grid backdrop. */
function initLightbox(root) {
    const dialog = root.querySelector('[data-lightbox]');

    if (!dialog) {
        return;
    }

    const panel = dialog.querySelector('[data-lightbox-panel]');
    const image = dialog.querySelector('[data-lightbox-image]');
    const name = dialog.querySelector('[data-lightbox-name]');
    const meta = dialog.querySelector('[data-lightbox-meta]');
    const count = dialog.querySelector('[data-lightbox-count]');
    const backgrounds = dialog.querySelectorAll('[data-lightbox-bg]');

    let entries = [];
    let index = 0;
    let preferredBackground = 'light';

    const setBackground = (background) => {
        panel.dataset.bg = background;
        backgrounds.forEach((button) => button.setAttribute('aria-pressed', String(button.dataset.lightboxBg === background)));
    };

    const show = (nextIndex, direction = 0) => {
        index = (nextIndex + entries.length) % entries.length;
        const entry = entries[index].dataset;

        image.src = entry.src;
        image.alt = `${entry.name} logo`;
        name.textContent = entry.name;
        meta.textContent = entry.meta;
        count.textContent = `${pad(index + 1)} / ${pad(entries.length)}`;
        setBackground(entry.tone === 'dark' ? 'dark' : preferredBackground);

        if (!reduceMotion) {
            gsap.fromTo(image, { autoAlpha: 0, x: direction * 48 }, { autoAlpha: 1, x: 0, duration: 0.9 });
        }
    };

    const close = () => {
        if (reduceMotion) {
            dialog.close();
            return;
        }

        gsap.to(panel, { autoAlpha: 0, duration: 0.4, ease: 'power2.in', onComplete: () => dialog.close() });
    };

    root.querySelectorAll('[data-logo-open]').forEach((button) => {
        button.addEventListener('click', () => {
            entries = [...root.querySelectorAll('[data-logo-open]')].filter((entry) => isShown(entry.closest('[data-logo-item]')));
            show(entries.indexOf(button));
            dialog.showModal();
            setScrollLocked(true);

            if (!reduceMotion) {
                gsap.fromTo(
                    panel,
                    { autoAlpha: 1, clipPath: 'inset(100% 0% 0% 0%)' },
                    { clipPath: 'inset(0% 0% 0% 0%)', duration: 1, ease: 'expo.inOut' },
                );
            }
        });
    });

    dialog.querySelectorAll('[data-lightbox-prev]').forEach((button) => button.addEventListener('click', () => show(index - 1, -1)));
    dialog.querySelectorAll('[data-lightbox-next]').forEach((button) => button.addEventListener('click', () => show(index + 1, 1)));
    dialog.querySelector('[data-lightbox-close]').addEventListener('click', close);

    backgrounds.forEach((button) => {
        button.addEventListener('click', () => {
            preferredBackground = button.dataset.lightboxBg;
            setBackground(preferredBackground);
        });
    });

    dialog.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowRight') {
            show(index + 1, 1);
        } else if (event.key === 'ArrowLeft') {
            show(index - 1, -1);
        } else if (event.key === 'Escape') {
            event.preventDefault();
            close();
        }
    });

    dialog.addEventListener('close', () => {
        gsap.set(panel, { clearProps: 'opacity,visibility,clipPath' });
        setScrollLocked(false);
    });
}

/** Construction lines draw themselves in once in view, and replay whenever the grid view is chosen again. */
function buildDrawing(stage) {
    const lines = stage.querySelectorAll('[data-line]');

    lines.forEach((line) => {
        const length = line.getTotalLength();
        gsap.set(line, { strokeDasharray: length, strokeDashoffset: length });
    });

    return gsap
        .timeline({ paused: true })
        .from(stage.querySelectorAll('[data-guide]'), { opacity: 0, duration: 1, stagger: 0.04, ease: 'power2.out' }, 0)
        .to(lines, { strokeDashoffset: 0, duration: 1.6, stagger: 0.07, ease: 'expo.inOut' }, 0.15)
        .from(stage.querySelectorAll('[data-dot], [data-label]'), { opacity: 0, duration: 0.8, stagger: 0.05 }, '-=0.7');
}

function initAnatomy(root) {
    root.querySelectorAll('[data-anatomy]').forEach((study) => {
        const stage = study.querySelector('[data-anatomy-stage]');
        const modes = study.querySelectorAll('[data-anatomy-mode]');
        const drawing = reduceMotion ? null : buildDrawing(stage);

        if (drawing) {
            ScrollTrigger.create({ trigger: stage, start: 'top 75%', once: true, onEnter: () => drawing.play() });
        }

        modes.forEach((button) => {
            button.addEventListener('click', () => {
                const mode = button.dataset.anatomyMode;

                modes.forEach((other) => other.setAttribute('aria-pressed', String(other === button)));
                stage.dataset.mode = mode;

                if (mode === 'construct') {
                    drawing?.restart();
                }
            });
        });
    });
}

function initSwatches(root) {
    root.querySelectorAll('[data-copy]').forEach((button) => {
        const label = button.querySelector('[data-copy-label]');
        let resetTimer;

        button.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(button.dataset.copy);
            } catch {
                return;
            }

            label.textContent = 'Copied';
            clearTimeout(resetTimer);
            resetTimer = setTimeout(() => {
                label.textContent = button.dataset.copy;
            }, 1400);
        });
    });
}

function initInUse(root) {
    root.querySelectorAll('[data-flip-card]').forEach((card) => {
        card.addEventListener('click', () => {
            card.setAttribute('aria-pressed', String(card.getAttribute('aria-pressed') !== 'true'));
        });
    });

    const icons = root.querySelectorAll('[data-app-icon]');

    if (icons.length && !reduceMotion) {
        gsap.from(icons, {
            autoAlpha: 0,
            scale: 0.6,
            duration: 1,
            stagger: 0.07,
            ease: 'back.out(1.6)',
            scrollTrigger: { trigger: icons[0], start: 'top 85%', once: true },
        });
    }
}

export function initLogoPortfolio() {
    const root = document.querySelector('[data-logo-portfolio]');

    if (!root) {
        return;
    }

    initIndex(root);
    initLightbox(root);
    initAnatomy(root);
    initSwatches(root);
    initInUse(root);
}
