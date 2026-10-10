<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;

/**
 * The six service pages. Copy mirrors content/pages/services/*.md; bracketed placeholders in
 * those files (timelines, scope notes, case studies) are intentionally left out until real answers exist.
 *
 * @phpstan-type Service array{
 *     slug: string, name: string, heading: string, accent: string, meta_title: string, meta_description: string,
 *     image: string, summary: string, hub_summary: string, hub_points: list<string>, scenario: string,
 *     intro: string, explainer: array{title: string, items: list<array{label: string, text: string}>}|null,
 *     included: list<string>, audience: list<array{label: string|null, text: string}>,
 *     process: list<array{title: string, text: string|null}>, faq: list<array{question: string, answer: string}>,
 *     related: array{slug: string, heading: string, text: string}, cta: string
 * }
 */
class ServiceCatalog
{
    /**
     * @return Collection<int, Fluent<string, mixed>>
     */
    public static function all(): Collection
    {
        return collect(self::services())->map(fn (array $service): Fluent => new Fluent($service));
    }

    /**
     * @return Fluent<string, mixed>|null
     */
    public static function find(string $slug): ?Fluent
    {
        return self::all()->firstWhere('slug', $slug);
    }

    /**
     * @return list<Service>
     */
    private static function services(): array
    {
        return [
            [
                'slug' => 'branding',
                'name' => 'Branding',
                'heading' => 'Branding design',
                'accent' => 'services',
                'meta_title' => 'Branding Design Services | The Ordinary Agency',
                'meta_description' => 'Brand strategy, identity, and visual systems built to last. See how we approach branding projects and start yours with a quick call.',
                'image' => 'img/home/branding.webp',
                'summary' => 'Identity, strategy, and visual systems that hold up everywhere your brand shows up.',
                'hub_summary' => 'Brand strategy, identity, and visual systems built to last — logo, color, typography, and the guidelines to keep it all consistent as you grow.',
                'hub_points' => ['Brand strategy and positioning', 'Logo and visual identity', 'Brand guidelines'],
                'scenario' => 'Launching a brand?',
                'intro' => 'Branding, to us, is the thinking and the system behind how your business looks, sounds, and feels — built so it holds up whether you’re launching for the first time or rebuilding from scratch.',
                'explainer' => null,
                'included' => ['Brand strategy and positioning', 'Logo and visual identity design', 'Color palette and typography system', 'Brand guidelines document'],
                'audience' => [
                    ['label' => 'A new business launching', 'text' => 'and needing an identity from zero.'],
                    ['label' => 'An existing business rebranding', 'text' => 'because the current identity no longer fits who you are.'],
                    ['label' => 'A product line needing its own identity', 'text' => 'within a larger brand family.'],
                ],
                'process' => [
                    ['title' => 'Discovery', 'text' => 'We learn your business, audience, and goals — and what the brand needs to communicate that it currently doesn’t.'],
                    ['title' => 'Concept', 'text' => 'We explore a small number of strong directions rather than dozens of weak ones, so early feedback is focused.'],
                    ['title' => 'Design & Refinement', 'text' => 'We build out the chosen direction into a full system — logo, color, type, applications — and refine it with your input.'],
                    ['title' => 'Handoff & Guidelines', 'text' => 'You receive final files and a guidelines document so the identity stays consistent as your team grows.'],
                ],
                'faq' => [
                    ['question' => 'What do you need from me to get started?', 'answer' => 'A clear sense of your business, audience, and any existing materials (old logo, brand assets, competitor examples you like or dislike).'],
                    ['question' => 'What happens after you deliver the brand?', 'answer' => 'You’ll receive final files and guidelines to use internally.'],
                    ['question' => 'What’s the difference between a logo refresh and a full rebrand?', 'answer' => 'A refresh updates the mark and visuals while keeping your core positioning. A full rebrand rethinks the strategy and identity together — usually because the business itself has changed.'],
                ],
                'related' => ['slug' => 'packaging-design', 'heading' => 'Need packaging too?', 'text' => 'If your branding project includes a physical product, pair this with packaging design.'],
                'cta' => 'Start a branding project',
            ],
            [
                'slug' => 'packaging-design',
                'name' => 'Packaging Design',
                'heading' => 'Packaging design',
                'accent' => 'services',
                'meta_title' => 'Packaging Design Services | The Ordinary Agency',
                'meta_description' => 'Shelf-ready packaging design, from concept to print-ready files. See our process and get a quote for your product’s packaging.',
                'image' => 'img/home/packaging.webp',
                'summary' => 'Shelf-ready packaging, from concept to print-ready files.',
                'hub_summary' => 'Packaging that earns the shelf (or the unboxing moment) — from concept exploration through print-ready files.',
                'hub_points' => ['Concept directions and structural input', 'Print-ready files with correct bleed and color specs', 'Dieline coordination'],
                'scenario' => 'Shipping a physical product?',
                'intro' => 'Packaging is the first physical moment someone has with your brand — on a shelf or on their doorstep. We design packaging that holds its own there, then hand off everything your printer needs.',
                'explainer' => null,
                'included' => ['Concept directions and structural input', 'Graphic design across all panels', 'Print-ready files with correct bleed and color specs', 'Dieline coordination with your printer'],
                'audience' => [
                    ['label' => 'A new product launching', 'text' => 'and needing packaging from scratch.'],
                    ['label' => 'An existing line refreshing', 'text' => 'its look without losing what customers already recognize.'],
                    ['label' => 'A multi-SKU product family', 'text' => 'that needs a consistent system across variants.'],
                ],
                'process' => [
                    ['title' => 'Discovery & Research', 'text' => 'We learn the product, the shelf or unboxing context, and what competitors are doing.'],
                    ['title' => 'Concept Directions', 'text' => 'We explore a small number of strong directions, grounded in how the packaging will actually be seen and used.'],
                    ['title' => 'Design Development', 'text' => 'We build out the chosen direction across every panel and variant.'],
                    ['title' => 'Production-Ready Handoff', 'text' => 'You receive print-ready files, correctly set up for your printer, plus a digital version.'],
                ],
                'faq' => [
                    ['question' => 'What files do I receive?', 'answer' => 'Print-ready files (correct bleed, color profile, dielines coordinated with your printer) plus a digital version.'],
                    ['question' => 'Can this scale to multiple SKUs?', 'answer' => 'Yes — we design a system that extends consistently across variants, not just a one-off design for a single product.'],
                ],
                'related' => ['slug' => 'branding', 'heading' => 'Need a brand first?', 'text' => 'If you don’t yet have a visual identity to build the packaging from, start with branding.'],
                'cta' => 'Start a packaging project',
            ],
            [
                'slug' => 'pitch-deck-design',
                'name' => 'Pitch Deck Design',
                'heading' => 'Pitch deck design',
                'accent' => 'services',
                'meta_title' => 'Pitch Deck Design Services | The Ordinary Agency',
                'meta_description' => 'Investor-ready pitch decks, designed to be clear and persuasive. See sample decks and our process, then start yours today.',
                'image' => 'img/home/pitch-deck.webp',
                'summary' => 'Investor-ready decks that are clear, not cluttered.',
                'hub_summary' => 'Investor- and sales-ready decks that are clear, visual, and built to hold attention — not just another slide template.',
                'hub_points' => ['Slide design and layout', 'Data visualization', 'Editable source files'],
                'scenario' => 'Raising funding or pitching a client?',
                'intro' => 'A pitch deck has one job: make your story easy to follow and hard to forget. We design decks for fundraising, sales, and board conversations that get out of the way of the story, not in front of it.',
                'explainer' => null,
                'included' => ['Slide design and layout', 'Data visualization and charts', 'Brand-aligned visual system', 'Editable source files (Figma, PowerPoint, or Keynote)'],
                'audience' => [
                    ['label' => 'Founders fundraising', 'text' => 'and needing an investor-ready deck.'],
                    ['label' => 'Sales teams', 'text' => 'needing a sharper, more consistent pitch.'],
                    ['label' => 'Teams presenting', 'text' => 'at a board meeting or major pitch.'],
                ],
                'process' => [
                    ['title' => 'Discovery & Content Review', 'text' => 'We review what you already have — notes, data, an existing rough deck — and understand your audience and goals.'],
                    ['title' => 'Structure & Storyboard', 'text' => 'We map the story slide by slide before any visual design starts, so the structure is right first.'],
                    ['title' => 'Design & Visualization', 'text' => 'We design every slide, including charts and data visualization, in your brand’s visual language.'],
                    ['title' => 'Delivery & Presenter Support', 'text' => 'You receive final files in your preferred format, ready to present.'],
                ],
                'faq' => [
                    ['question' => 'What file formats do you deliver?', 'answer' => 'Editable source files in your preferred format (Figma, PowerPoint, or Keynote), plus a presentation-ready PDF.'],
                ],
                'related' => ['slug' => 'branding', 'heading' => 'Need a brand system for your deck?', 'text' => 'If your visual identity isn’t established yet, pair this with branding.'],
                'cta' => 'Start your pitch deck',
            ],
            [
                'slug' => 'social-media-design',
                'name' => 'Social Media Design',
                'heading' => 'Social media design',
                'accent' => 'services',
                'meta_title' => 'Social Media Design Services | The Ordinary Agency',
                'meta_description' => 'On-brand templates and graphics for Instagram, LinkedIn, and more. See our process and samples, then start your social design project.',
                'image' => 'img/home/social-media.webp',
                'summary' => 'On-brand templates and graphics for every platform you post on.',
                'hub_summary' => 'On-brand templates and graphics so your feed looks considered, not improvised — built for the platforms you actually post on.',
                'hub_points' => ['Post and carousel templates', 'Profile and banner design', 'Reusable brand-kit system'],
                'scenario' => 'Growing your audience online?',
                'intro' => 'We design the visuals — templates, graphics, and systems that make your feed look considered instead of improvised.',
                'explainer' => null,
                'included' => ['Post and carousel templates', 'Profile and banner design', 'Platform-specific sizing (Instagram, LinkedIn, TikTok/Reels covers)', 'A reusable brand-kit/template system you can use going forward'],
                'audience' => [
                    ['label' => 'Brands needing a consistent visual system', 'text' => 'to post themselves, ongoing.'],
                    ['label' => 'A specific campaign', 'text' => 'needing a batch of on-brand graphics.'],
                ],
                'process' => [
                    ['title' => 'Discovery & Brand Review', 'text' => 'We look at your existing brand (or build from your branding project) and your current social presence.'],
                    ['title' => 'Template & Style Direction', 'text' => 'We design the visual direction and a small set of flexible templates.'],
                    ['title' => 'Design Production', 'text' => 'We build out the full set of templates and/or campaign graphics.'],
                    ['title' => 'Handoff', 'text' => 'You receive editable files or a ready-to-use template system.'],
                ],
                'faq' => [
                    ['question' => 'Which platforms do you design for?', 'answer' => 'Instagram, LinkedIn, and other platforms you actually post on — tell us which ones matter to you.'],
                    ['question' => 'What files do I get, and can I edit them myself?', 'answer' => 'You’ll receive editable source files or a template system you can reuse without coming back to us for every post.'],
                ],
                'related' => ['slug' => 'branding', 'heading' => 'Need a brand system first?', 'text' => 'If you don’t have an established visual identity yet, start with branding.'],
                'cta' => 'Start your social design project',
            ],
            [
                'slug' => 'brochure-design',
                'name' => 'Brochure Design',
                'heading' => 'Brochure design',
                'accent' => 'services',
                'meta_title' => 'Brochure Design Services | The Ordinary Agency',
                'meta_description' => 'Tri-fold, bi-fold, and sales sheet brochure design that’s print-ready and on-brand. See our process and start your brochure project.',
                'image' => 'img/home/brochure.webp',
                'summary' => 'Print and digital collateral that keeps selling after you leave the room.',
                'hub_summary' => 'Print and digital collateral that keeps selling after the conversation ends — tri-fold, bi-fold, or single-sheet.',
                'hub_points' => ['Layout and design', 'Print-ready files plus a digital version', 'Format guidance for your use case'],
                'scenario' => 'Need printed or PDF collateral?',
                'intro' => 'A good brochure keeps selling after you’ve left the room. We design print and digital collateral that’s clear, on-brand, and ready to hand to your printer — or to a prospect’s inbox.',
                'explainer' => [
                    'title' => 'Which format fits your use case?',
                    'items' => [
                        ['label' => 'Tri-fold', 'text' => 'Compact, good for mailers and takeaways.'],
                        ['label' => 'Bi-fold', 'text' => 'Simple, good for a single clear offer or overview.'],
                        ['label' => 'Multi-page booklet', 'text' => 'More room, good for detailed catalogs or portfolios.'],
                        ['label' => 'Single-sheet', 'text' => 'Quick reference, good for trade show handouts or leave-behinds.'],
                    ],
                ],
                'included' => ['Layout and design', 'Brand-consistent typography and imagery', 'Print-ready files (correct bleed and color profile) plus a digital version'],
                'audience' => [
                    ['label' => null, 'text' => 'Trade show handouts'],
                    ['label' => null, 'text' => 'Sales leave-behinds'],
                    ['label' => null, 'text' => 'Mailers'],
                    ['label' => null, 'text' => 'Informational one-pagers'],
                ],
                'process' => [
                    ['title' => 'Discovery', 'text' => 'We confirm the goal, audience, and best format for your use case.'],
                    ['title' => 'Copy & Content', 'text' => null],
                    ['title' => 'Design Execution', 'text' => 'We design the brochure in your brand’s visual language.'],
                    ['title' => 'Delivery', 'text' => 'You receive print-ready files plus a digital/PDF version.'],
                ],
                'faq' => [
                    ['question' => 'Can I get a digital-only version?', 'answer' => 'Yes — every brochure includes a digital/PDF version alongside the print-ready files.'],
                ],
                'related' => ['slug' => 'branding', 'heading' => 'Need a brand system first?', 'text' => 'If you don’t yet have a visual identity, start with branding.'],
                'cta' => 'Start your brochure project',
            ],
            [
                'slug' => 'ui-ux-design',
                'name' => 'UI/UX Design',
                'heading' => 'UI/UX design',
                'accent' => 'services',
                'meta_title' => 'UI/UX Design Services | The Ordinary Agency',
                'meta_description' => 'Research-led UX and polished UI for web and mobile products. See our process and recent work, then start your product design project.',
                'image' => 'img/home/ui-ux.webp',
                'summary' => 'Research-led, carefully designed digital products.',
                'hub_summary' => 'Research-led UX and carefully designed UI for web and mobile products — from first wireframe to developer handoff.',
                'hub_points' => ['UX research and information architecture', 'Wireframes and prototypes', 'UI design and design systems'],
                'scenario' => 'Building or redesigning a digital product?',
                'intro' => 'We design digital products — websites, apps, and dashboards — grounded in research and refined into interfaces that feel obvious to use.',
                'explainer' => [
                    'title' => 'UX and UI, plainly',
                    'items' => [
                        ['label' => 'UX', 'text' => 'The research and structure behind a product — how it’s organized, how people move through it, what it needs to do.'],
                        ['label' => 'UI', 'text' => 'The visual interface built on top of that structure — what it looks like, how it feels to use. We handle both, together, so they’re never out of sync.'],
                    ],
                ],
                'included' => ['UX research and information architecture', 'Wireframes and prototypes', 'UI design and design systems', 'Responsive/mobile design', 'Developer handoff files'],
                'audience' => [
                    ['label' => 'A new product or MVP', 'text' => 'that needs to be designed from scratch.'],
                    ['label' => 'An existing product redesign', 'text' => 'that’s grown messy or outdated.'],
                    ['label' => 'A design system', 'text' => 'for a team that’s scaling and needs consistency.'],
                ],
                'process' => [
                    ['title' => 'Discovery & Research', 'text' => 'We understand your users, goals, and the problem the product needs to solve.'],
                    ['title' => 'Wireframes & Flows', 'text' => 'We map the structure and core flows before any visual design starts.'],
                    ['title' => 'UI Design & Prototyping', 'text' => 'We design the interface and build interactive prototypes to test before build.'],
                    ['title' => 'Handoff & Developer Support', 'text' => 'You receive organized, developer-ready files and documentation.'],
                ],
                'faq' => [
                    ['question' => 'What tools do you use?', 'answer' => 'Figma, primarily, for design and prototyping.'],
                    ['question' => 'How does developer handoff work?', 'answer' => 'You receive organized Figma files with specs, assets, and documentation ready for your development team.'],
                ],
                'related' => ['slug' => 'branding', 'heading' => 'Need a brand system first?', 'text' => 'If your visual identity isn’t established yet, start with branding.'],
                'cta' => 'Start your product design project',
            ],
        ];
    }
}
