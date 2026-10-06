# The Ordinary Agency — Design System

A specification for how this site looks, moves, and is built. Written against the real copy in `content/pages/`, the existing logo assets in `public/img/logo/`, and the Laravel + Tailwind v4 stack already wired up in this repo. Every section below should be directly implementable in `resources/views` and `resources/css/app.css` without guessing.

---

## 1. Principles

Five rules, pulled from the studio's own copy (`content/pages/home.md`, `content/pages/about.md`) rather than invented:

1. **Nothing that isn't earning its place.** "Minimal doesn't mean empty — it means nothing is on the page that isn't earning its place." Every section, animation, and pixel needs a reason.
2. **Clarity over decoration.** No gradients, no accent colors, no ornamental icons. The mark, the type, and negative space do the work.
3. **Considered motion, not spectacle.** Motion exists to reveal structure and add polish — slow, quiet, confident. Never flashy, never distracting from copy.
4. **Black and white, full stop.** No secondary brand color. Contrast and grayscale tone are the entire visual palette.
5. **Content leads, chrome follows.** The six services and the work are the product. UI recedes; copy and case studies are the hero.

---

## 2. Logo & Mark

Source files (already in repo, do not regenerate):

| File | Size | Use |
|---|---|---|
| `public/img/logo/logo-light.png` | 344×329, RGBA | **Black mark** — for light backgrounds (white/off-white sections) |
| `public/img/logo/logo-dark.png` | 344×329, RGBA | **White mark** — for dark/black backgrounds (hero, footer, dark sections) |
| `public/img/logo/logo-favicon-light.png` | 344×344, RGBA | Favicon variant, light context |
| `public/img/logo/logo-favicon-dark.png` | 344×344, RGBA | Favicon variant, dark context |

The mark is a single asymmetric ring — an organic, hand-drawn "O." No wordmark is baked into the file, so the logotype in navigation/footer should be set as live text ("The Ordinary Agency") in Instrument Sans next to the mark, not as an image, for crisp rendering and accessibility.

**Naming convention to remember:** the suffix describes the *background* the file is designed to sit on, not the mark's own color — `-light` is the dark mark for light backgrounds, `-dark` is the light mark for dark backgrounds. Pick the file by the section's background, every time.

**Rules**
- Clear space around the mark ≥ the height of the ring on all sides. Never crowd it against text or the viewport edge.
- Minimum display size: 24px tall (favicon/nav), never scale below that.
- Never recolor the PNGs, never add drop shadows, never place on a busy photograph without a solid-color scrim behind it.
- On the video hero (see §6), the mark sits in the nav bar over the video — use `logo-dark.png` (white) with a subtle drop-shadow-free scrim (gradient overlay already darkens the video top edge, see §6) rather than adding a shadow to the mark itself.
- Favicon: replace the empty `public/favicon.ico` with a generated multi-size `.ico` from `logo-favicon-dark.png` (reads clearly at 16px on both light and dark browser chrome).

---

## 3. Color System

Strictly monochrome — no hue anywhere in the UI (photography/video grayscale treatment is covered in §7). Two layers: primitives (raw) and semantic (purpose), matching the three-layer token convention (primitive → semantic → component) this project's tooling expects.

```css
/* resources/css/app.css — extend the existing @theme block */
@theme {
    --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji',
        'Segoe UI Symbol', 'Noto Color Emoji';

    /* Primitives */
    --color-black: #0a0a0a;      /* true black, slightly softened to reduce OLED smear on video */
    --color-white: #fafafa;      /* true white, slightly softened to cut glare */
    --color-gray-900: #141414;
    --color-gray-700: #3d3d3d;
    --color-gray-500: #757575;
    --color-gray-300: #c7c7c7;
    --color-gray-100: #ececec;

    /* Semantic */
    --color-bg: var(--color-white);
    --color-bg-inverse: var(--color-black);
    --color-fg: var(--color-black);
    --color-fg-inverse: var(--color-white);
    --color-fg-muted: var(--color-gray-500);
    --color-border: var(--color-gray-300);
    --color-border-inverse: var(--color-gray-700);
    --color-overlay: color-mix(in srgb, var(--color-black) 55%, transparent);
}
```

**Usage rules**
- Default page background is `--color-bg` (off-white) with `--color-fg` (off-black) text — this is the state for ~90% of the site.
- Dark sections (hero, footer, "Clients"/closing CTA band) invert: `--color-bg-inverse` background, `--color-fg-inverse` text, `logo-dark.png` mark.
- `--color-fg-muted` is for secondary copy (meta text, captions, filter labels like the `[All] [Branding] …` filters in `work.md`/`journal.md`) — never for body copy.
- No focus-ring color beyond `--color-fg` — use a 2px solid outline in the current text color, offset 2px, to keep full-contrast accessibility without introducing a new hue.
- `--color-overlay` is reserved for the gradient scrim over the hero video (see §6), not for general card overlays.

---

## 4. Typography

Instrument Sans is already configured via Bunny Fonts in `vite.config.js` with weights **400, 500, 600** — do not add new weights/files to the font pipeline without approval, since that edits a shared config file. Design within those three weights:

| Role | Weight | Tracking | Notes |
|---|---|---|---|
| Display / H1 | 600 | -0.02em | Sentence case, lowercase-led — matches live copy exactly, e.g. "Branding and design agency for brands that want to stand out." Never set in all-caps. |
| H2 (section titles) | 600 | -0.01em | e.g. "What we do," "How we work" |
| H3 (card/service titles) | 500 | 0 | e.g. service names, work card titles |
| Body | 400 | 0 | Content paragraphs, 1.6 line-height |
| Small / meta | 400 | 0.01em | Captions, filter tags, dates — paired with `--color-fg-muted` |
| Link / CTA | 500 | 0 | Always with the arrow glyph already in the copy ("Book a call →") — see §8 for the underline-wipe interaction |

**Scale** (Tailwind v4 `@theme` additions, fluid via `clamp()` so the hero headline scales smoothly instead of jumping at breakpoints):

```css
@theme {
    --text-display: clamp(2.5rem, 2rem + 2.2vw, 4.5rem);
    --text-h1: clamp(2rem, 1.7rem + 1.4vw, 3rem);
    --text-h2: clamp(1.5rem, 1.35rem + 0.7vw, 2rem);
    --text-h3: 1.25rem;
    --text-body: 1.0625rem;
    --text-small: 0.875rem;
}
```

Keep every headline in the site's existing sentence-case voice (never title case, never uppercase) — this is already how every file in `content/pages/` is written and is a brand signal worth protecting in markup, not just copy.

---

## 5. Layout & Grid

- **Container:** max-width `1200px`, horizontal padding `clamp(1.25rem, 4vw, 4rem)`.
- **Grid:** 12-column CSS grid for section layout, 4-column on mobile. Service strip and work grid use `auto-fit`/`minmax()` card grids (3-up desktop, 2-up tablet, 1-up mobile) rather than fixed column counts, so the grid survives a variable number of case studies/articles.
- **Spacing scale:** 4px base — `4, 8, 12, 16, 24, 32, 48, 64, 96, 128`. Section vertical rhythm uses the top of that scale (`64–128px` between major sections) to keep the "considered, not crowded" feel; card/text spacing stays in the `8–24px` range.
- **Breakpoints:** Tailwind v4 defaults — `sm 640px`, `md 768px`, `lg 1024px`, `xl 1280px`, `2xl 1536px`. Design mobile-first; the hero video (§6) needs an explicit mobile fallback below `md`.
- **Dividers:** the copy already uses `---` (horizontal rule) as a literal section break in every `content/pages/*.md` file. Render these as a 1px `--color-border` rule with generous vertical margin, not a heavy visual divider — reinforce rhythm, don't decorate.

---

## 6. Motion & Video System

This is the centerpiece of the brief: a minimal, elegant landing page carried by a motion/video hero rather than static imagery or copy volume (the homepage SEO brief already calls for ~650–750 words total — motion does the persuasive work copy would otherwise have to).

### 6.1 Hero video

- **Content direction:** abstract, monochrome, slow. Options in order of preference: (a) extreme slow-motion footage of material/craft relevant to the six services (ink spreading, paper folding, print being pulled, packaging being assembled) graded to pure black-and-white; (b) a slow, looping morph/breathing animation of the logo ring itself; (c) desaturated slow-pan footage of real finished work once case studies exist. Never stock "team laughing at laptop" footage.
- **Format:** `video/webm` (VP9) primary + `video/mp4` (H.264) fallback, served via `<picture>`-style `<video>` with both `<source>`s. Target ≤ 6MB for an 8–15s loop at 1080p — compress hard; this is a hero background, not a feature film.
- **Behavior:** `autoplay muted loop playsinline`, no controls. A `poster` attribute set to a still frame (grayscale JPG) covers the load gap and is what search engines/social previews see.
- **Overlay:** a fixed gradient scrim (`linear-gradient(to bottom, var(--color-overlay), transparent 40%, var(--color-overlay) 100%)`) sits between the video and the nav/headline so white text and the `logo-dark.png` mark stay legible regardless of the footage underneath.
- **Mobile fallback:** below `md` (768px), swap the `<video>` for the `poster` still image. Mobile data costs and battery drain from an always-playing hero video aren't worth it when the viewport is too small to appreciate the motion anyway.
- **Reduced motion:** wrap the `<video>` in a `@media (prefers-reduced-motion: reduce)` check (JS: `matchMedia`) — if set, don't attach `autoplay`/don't call `.play()`, show the poster frame only. This is a correctness requirement, not optional polish.
- **Lazy strategy:** the hero video loads eager (it's above the fold by definition). Any other in-page video (e.g. a future case-study process reel) must use `loading="lazy"` equivalent — IntersectionObserver-gated `src` assignment — so it doesn't compete with the hero for bandwidth.

### 6.2 Scroll reveal

- Sections fade+rise on entry: `opacity 0 → 1`, `translateY(16px) → 0`, via IntersectionObserver adding a class (no scroll-jank libraries needed — this is a one-screen-worth of JS, not a dependency).
- Stagger children (service cards, work cards) by `60ms` per item, capped at 6 items so a long grid doesn't feel sluggish to finish animating.
- Duration/easing tokens:

```css
@theme {
    --ease-elegant: cubic-bezier(0.22, 1, 0.36, 1); /* confident deceleration, no bounce */
    --duration-fast: 150ms;
    --duration-base: 350ms;
    --duration-slow: 600ms;
}
```

- Respect `prefers-reduced-motion` globally: when set, skip the IntersectionObserver transform entirely and render sections in their final state immediately — don't just shorten the duration to near-zero, actually remove the motion.

### 6.3 Micro-interactions

- **Links/CTAs:** the copy's recurring `→` arrow (every CTA in `content/pages/` ends in one) animates — arrow nudges 4px right on hover, underline wipes in left-to-right beneath the label, `--duration-fast`/`--ease-elegant`. This single interaction pattern should be the *only* link hover style site-wide for consistency.
- **Nav:** transparent over the hero video, crossfades to a solid `--color-bg` / `--color-fg` bar with a `--color-border` bottom hairline once scrolled past the hero (IntersectionObserver on the hero section, not a scroll-position pixel threshold, so it stays correct across viewport sizes).
- **Work/case-study thumbnails:** subtle scale (`1 → 1.03`) and the grayscale treatment from §7 lightening slightly on hover — a quiet "this is interactive" cue, not a flashy zoom.
- **Buttons:** ghost style only (1px border, transparent fill) — on hover, invert fill/text color for a crisp black↔white flip rather than a shadow or color shift. This is the one place pure color-inversion (not opacity) should be used, and it doubles as a brand-reinforcing moment.

### 6.4 Page transitions

Keep this native and dependency-free: use the browser's View Transitions API (`document.startViewTransition`) for a soft cross-fade between full page loads where supported, with no fallback needed since unsupported browsers simply get an instant navigation (progressive enhancement, zero risk). Do **not** reach for a client-side router or a JS animation library (GSAP, Lenis, Framer Motion) for this — it would mean adding a new frontend dependency, which needs explicit approval first per this project's conventions, and nothing above requires it.

---

## 7. Imagery & Photography

- All photography (case studies, team photos once added) is desaturated to grayscale via CSS (`filter: grayscale(1)`) rather than pre-exported as grayscale files, so source images stay reusable/full-color in client deliverables while the site stays monochrome.
- Optional refinement once real work exists: work-grid thumbnails sit at `grayscale(1)` by default and ease to `grayscale(0.4)` on hover/focus — a restrained way to hint at the real, colorful work behind a black-and-white site without breaking the brand palette.
- No stock photography. Every image on the site should be real studio/client work, consistent with the E-E-A-T requirements already written into the content briefs (`content/briefs/*.md`) — this is a content rule as much as a design one.
- Case-study and work images should be presented full-bleed or generously matted (large surrounding whitespace), never cropped into small decorative thumbnails — the "fewer words, stronger visual proof" positioning from the homepage brief depends on big, confident images.

---

## 8. Components

| Component | Spec |
|---|---|
| **Nav** | Logo mark + text lockup left, 2–3 top-level links + "Book a call" ghost button right. Transparent-over-video → solid-on-scroll per §6.3. Mobile: collapses to a single-line menu button, full-screen overlay menu on open (no slide-out drawer — keep it full and quiet). |
| **Button (primary)** | Ghost style, 1px border in current context color, invert-fill on hover (§6.3). Used for every CTA — "Book a call," "See our work," etc. |
| **Button (text link)** | Underline-wipe + arrow nudge (§6.3). Used inline within copy, e.g. "explore our design services." |
| **Service card** | Title (H3) + 2–3 line description + text-link CTA. No icons — six services are differentiated by name and copy alone, matching the brief's instruction to avoid "vague" category icons competitors use. |
| **Work card** | Image (grayscale per §7) + client/project name + discipline tag (`--text-small`, `--color-fg-muted`) + one-line outcome. Links to `/work/{slug}`. |
| **Journal card** | Title + excerpt + category tag + date, same visual weight as work cards for consistency. |
| **Filter bar** (`/work`, `/journal`) | Plain text pills, active state = underline, not a filled badge — avoids introducing a second "selected" color. |
| **Footer** | Dark section (`--color-bg-inverse`), `logo-dark.png`, sitemap links, contact fallback. Mirrors the closing-CTA dark band on the homepage so the last thing a visitor sees before and after the footer is visually continuous. |
| **Dark CTA band** | Used for homepage "Have a project in mind?" closing section and any mid-page proof/clients band — full-bleed `--color-bg-inverse`, large centered headline, single ghost button in inverse colors. |

---

## 9. Page-by-page direction

Mapped directly to the finished copy in `content/pages/`, not re-invented:

- **Home (`/`)** — Hero: full-bleed motion video (§6.1) with H1 "Branding and design agency for brands that want to stand out," single ghost CTA. Then, in order: services strip (6 cards, §8), recent work grid (conditionally rendered — the copy itself says launch only once real case studies exist), philosophy section ("Design that's considered, not crowded") as a quiet text-only break with generous whitespace, 4-step process teaser, dark clients/proof band (conditional, same rule), dark closing CTA band.
- **Services (`/services`)** — Light page. Intro paragraph, then the "which service do you need" scenario guide as a simple 2-column list (scenario → service link), then six service sections in document order (matches the brief's mandated coverage of all six), each ending in its own CTA link. No hero video here — motion budget is spent on the homepage; inner pages stay calm.
- **Service detail (`/services/{slug}`)** — Standard content page: H1 service name, description, deliverables list, CTA. Reuses the ghost-button and text-link patterns, no new components.
- **Work (`/work`)** — Filter bar (§8) + work card grid (§8). Grid is the whole page; keep chrome minimal so images carry it.
- **Process (`/process`)** — Four numbered sections (Discovery/Concept/Design & Refinement/Delivery), rendered as a vertical timeline using the divider rule from §5 between steps rather than cards — this page is sequential, not a grid.
- **About (`/about`)** — Story section, philosophy quote-style callout (reuse the exact "nothing on the page that isn't earning its place" line as a pull-quote — it's the brand's clearest articulation of itself), team grid (conditional on real names/photos per the content brief), links out to work/process.
- **Journal (`/journal`)** — Same filter + grid pattern as Work, using journal cards (§8).
- **Contact (`/contact`)** — Light page, no hero. Scheduler embed placeholder, "what to expect" text block, direct-contact fallback. Keep this page the fastest-loading, lowest-motion page on the site — it's the conversion endpoint, don't make someone wait on it.

---

## 10. Implementation notes (Laravel + Tailwind v4)

- Add the `@theme` extensions from §3, §4, and §6.2 directly into `resources/css/app.css` below the existing block — Tailwind v4 is CSS-first, no `tailwind.config.js` to touch.
- Build views as Blade partials/components under `resources/views/` mirroring the sitemap above (`resources/views/home.blade.php`, `resources/views/services/index.blade.php`, `resources/views/services/show.blade.php`, etc.) with shared `layout`, `nav`, and `footer` components so the dark/light section logic in §3 lives in one place.
- Motion JS (IntersectionObserver reveal, reduced-motion check, nav crossfade, View Transitions hook) belongs in `resources/js/app.js` as vanilla JS — no new npm dependency is needed for anything specified in §6, and none should be added without approval first.
- Replace the placeholder `public/favicon.ico` using `logo-favicon-dark.png` as the source.
- `content/pages/*.md` is the copy source of truth — when a page says a section is conditional ("omit until real case studies exist," "omit until you have genuine testimonials"), the Blade view should actually conditionally render it, not ship with placeholder content.

---

## 11. Accessibility & performance checklist

- [ ] Black/white semantic tokens meet WCAG AAA contrast by construction — don't introduce a gray lighter than `--color-gray-500` for body text.
- [ ] Hero video: `muted`, `playsinline`, no autoplay (and no `.play()` call) when `prefers-reduced-motion: reduce`.
- [ ] All scroll-reveal motion is skipped (not just shortened) under reduced motion.
- [ ] Hero video has a real `poster` frame — never a blank/black flash before load.
- [ ] Focus states use a visible outline (§3) — ghost buttons and text links must not rely on hover-only affordance.
- [ ] Video assets are compressed and served in next-gen formats (webm) with an mp4 fallback; total hero payload (video + poster) budgeted under ~6MB.
- [ ] Below-the-fold video/images lazy-load; nothing competes with the hero for initial bandwidth.
- [ ] Logo files keep their alpha transparency — never flatten onto a white rectangle when exporting for use elsewhere.

---

*This document is the design source of truth until superseded. Pair it with `content/pages/*.md` for copy and `content/briefs/*.md` for the SEO/strategy reasoning behind each page's structure.*
