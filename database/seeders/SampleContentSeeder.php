<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * Fictional work and journal entries for local development. Never run this in production.
 * Images are copied onto the public disk so records look exactly like admin uploads.
 */
class SampleContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedProjects();
        $this->seedPosts();
    }

    private function seedProjects(): void
    {
        $projects = [
            [
                'title' => 'Morning Ritual', 'slug' => 'morning-ritual', 'client' => 'Halden Coffee Co.',
                'discipline' => 'Packaging Design', 'services' => ['Packaging Design', 'Branding'], 'year' => 2026,
                'summary' => 'A calm, typographic packaging system for a specialty roaster.',
                'cover' => 'img/sample/work-halden.webp', 'gallery' => ['img/home/packaging.webp', 'img/home/philosophy.webp'],
                'is_featured' => true,
                'body' => "## The brief\n\nA small-batch roaster wanted bags that felt as considered as the coffee inside, without the craft-coffee clichés crowding the shelf.\n\n## The approach\n\nOne typeface, one colour per origin, and a label system that puts the tasting notes first. Every bag shares a grid, so new origins slot in without a redesign.\n\n## The outcome\n\nA packaging system that scales across every origin and reads clearly from across the room.",
            ],
            [
                'title' => 'Quiet Objects', 'slug' => 'quiet-objects', 'client' => 'Fold Studio',
                'discipline' => 'Branding', 'services' => ['Branding', 'Social Media Design'], 'year' => 2026,
                'summary' => 'Identity and visual system for an everyday-carry label.',
                'cover' => 'img/sample/work-fold.webp', 'gallery' => ['img/home/branding.webp', 'img/sample/work-lens.webp'],
                'is_featured' => true,
                'body' => "## The brief\n\nAn everyday-carry label needed an identity as unfussy as its products.\n\n## The approach\n\nA restrained wordmark, a neutral palette, and photography rules that let the objects speak.\n\n## The outcome\n\nA brand system with guidelines the in-house team now uses for every launch.",
            ],
            [
                'title' => 'Into the Light', 'slug' => 'into-the-light', 'client' => 'Atrium Developments',
                'discipline' => 'Pitch Deck Design', 'services' => ['Pitch Deck Design'], 'year' => 2025,
                'summary' => 'An investor deck that turns floor plans into a story.',
                'cover' => 'img/sample/work-atrium.webp', 'gallery' => ['img/home/pitch-deck.webp', 'img/home/process.webp'],
                'is_featured' => false,
                'body' => "## The brief\n\nA property developer had the numbers but not the narrative.\n\n## The approach\n\nWe storyboarded the deck before designing a single slide, then built clear charts around the three figures investors asked about most.\n\n## The outcome\n\nA deck that walks a room from site to return in twelve slides.",
            ],
            [
                'title' => 'Parts & Pieces', 'slug' => 'parts-and-pieces', 'client' => 'Lens Supply',
                'discipline' => 'UI/UX Design', 'services' => ['UI/UX Design'], 'year' => 2025,
                'summary' => 'A storefront for a camera repair specialist, built around the parts.',
                'cover' => 'img/sample/work-lens.webp', 'gallery' => ['img/home/ui-ux.webp', 'img/home/social-media.webp'],
                'is_featured' => false,
                'body' => "## The brief\n\nA repair specialist's catalogue had outgrown its storefront.\n\n## The approach\n\nWe mapped how customers search — by camera model first — and rebuilt navigation around it, then designed a component library for the team to extend.\n\n## The outcome\n\nA storefront where finding the right part takes a few taps, not a phone call.",
            ],
        ];

        foreach ($projects as $index => $project) {
            Project::updateOrCreate(['slug' => $project['slug']], [
                ...collect($project)->except(['cover', 'gallery', 'body'])->all(),
                'content' => $this->contentFor($project),
                'cover_image' => $this->copyToDisk($project['cover'], 'projects'),
                'gallery' => collect($project['gallery'])->map(fn (string $path) => $this->copyToDisk($path, 'projects/gallery'))->all(),
                'sort_order' => $index,
                'published_at' => now()->subWeeks($index + 1),
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $project
     * @return list<array{type: string, data: array<string, mixed>}>
     */
    private function contentFor(array $project): array
    {
        $blocks = [['type' => 'markdown', 'data' => ['body' => $project['body']]]];

        if ($project['slug'] !== 'into-the-light') {
            return $blocks;
        }

        // Demo of the media + custom HTML blocks: an image, then an isolated animated embed using an uploaded asset.
        $asset = $this->copyToDisk('img/home/pitch-deck.webp', 'projects/embeds');

        return [
            ...$blocks,
            ['type' => 'image', 'data' => [
                'image' => $this->copyToDisk('img/home/process.webp', 'projects/blocks'),
                'alt' => 'Escalators rising through a symmetrical modern station',
                'caption' => 'The narrative moves the way the building does — one level at a time.',
                'width' => 'wide',
            ]],
            ['type' => 'html', 'data' => [
                'mode' => 'isolated',
                'width' => 'wide',
                'height' => null,
                'assets' => [$asset],
                'asset_names' => [$asset => 'deck-cover.webp'],
                'html' => <<<'HTML'
                <section class="stats">
                  <img class="bg" src="[[asset:deck-cover.webp]]" alt="">
                  <div class="grid">
                    <div><span class="num" data-to="12">0</span><p>slides, start to finish</p></div>
                    <div><span class="num" data-to="3">0</span><p>figures investors asked about</p></div>
                    <div><span class="num" data-to="1">0</span><p>story they remembered</p></div>
                  </div>
                </section>
                HTML,
                'css' => <<<'CSS'
                .stats { position: relative; overflow: hidden; background: #0a0a0a; color: #f5f5f3; padding: 64px 32px; }
                .bg { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: .25; }
                .grid { position: relative; display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 32px; }
                .num { font-size: clamp(56px, 10vw, 120px); font-weight: 300; line-height: 1; display: block; }
                p { margin: 12px 0 0; opacity: .7; }
                CSS,
                'js' => <<<'JS'
                document.querySelectorAll('.num').forEach((el) => {
                  const target = Number(el.dataset.to);
                  const start = performance.now();
                  const tick = (now) => {
                    const t = Math.min((now - start) / 1400, 1);
                    el.textContent = Math.round(target * (1 - Math.pow(1 - t, 3)));
                    if (t < 1) requestAnimationFrame(tick);
                  };
                  requestAnimationFrame(tick);
                });
                JS,
            ]],
        ];
    }

    private function seedPosts(): void
    {
        $posts = [
            [
                'title' => 'Why we start every brand with a single sentence', 'slug' => 'start-with-a-single-sentence',
                'category' => 'Branding & Identity', 'cover' => 'img/sample/journal-one-sentence.webp', 'published_at' => '2026-09-18',
                'excerpt' => 'Before logos or colour, we write down what the brand needs to say — in one line.',
                'body' => "Every identity we design starts the same way: one sentence that says what the brand is for.\n\n## Why one sentence\n\nIf it can't be said simply, it can't be designed simply. The sentence becomes the test every later decision has to pass.\n\n## What happens next\n\nOnly once the sentence holds up do we open a sketchbook.",
            ],
            [
                'title' => 'Designing packaging for the shelf, not the screen', 'slug' => 'packaging-for-the-shelf',
                'category' => 'Packaging', 'cover' => 'img/sample/journal-shelf.webp', 'published_at' => '2026-08-27',
                'excerpt' => 'A render can flatter anything. A shelf, three metres away, cannot.',
                'body' => "Renders are generous. Shelves are not.\n\n## Print it early\n\nWe print concepts at full size in week one and stand them next to competitors.\n\n## Design for distance\n\nIf the product can't be identified from three metres away, the detail on the back panel won't save it.",
            ],
            [
                'title' => 'The case for fewer concepts', 'slug' => 'the-case-for-fewer-concepts',
                'category' => 'Process & Craft', 'cover' => 'img/sample/journal-fewer-concepts.webp', 'published_at' => '2026-08-04',
                'excerpt' => 'Why we show a small number of strong directions instead of dozens of weak ones.',
                'body' => "More options feel generous, but they rarely help.\n\n## Fewer, stronger\n\nA small number of considered directions makes feedback focused instead of overwhelming.\n\n## The result\n\nDecisions get made faster, and the final work is sharper for it.",
            ],
        ];

        foreach ($posts as $post) {
            $category = Category::firstOrCreate(['name' => $post['category']]);

            Post::updateOrCreate(['slug' => $post['slug']], [
                'category_id' => $category->id,
                'title' => $post['title'],
                'excerpt' => $post['excerpt'],
                'body' => $post['body'],
                'cover_image' => $this->copyToDisk($post['cover'], 'posts'),
                'published_at' => Carbon::parse($post['published_at']),
            ]);
        }
    }

    /** Copies a bundled public image onto the public disk and returns its disk path. */
    private function copyToDisk(string $publicPath, string $directory): string
    {
        $diskPath = $directory.'/'.basename($publicPath);

        if (! Storage::disk('public')->exists($diskPath)) {
            Storage::disk('public')->put($diskPath, file_get_contents(public_path($publicPath)));
        }

        return $diskPath;
    }
}
