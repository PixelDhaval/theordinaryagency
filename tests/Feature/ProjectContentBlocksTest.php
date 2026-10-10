<?php

use App\Models\Project;
use App\Support\HtmlEmbed;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->withoutVite();
});

function htmlBlock(array $data): array
{
    return ['type' => 'html', 'data' => [
        'mode' => HtmlEmbed::MODE_ISOLATED,
        'width' => 'wide',
        'assets' => ['projects/embeds/a1b2c3.webp'],
        'asset_names' => ['projects/embeds/a1b2c3.webp' => 'hero.webp'],
        ...$data,
    ]];
}

test('asset tokens resolve to uploaded file URLs and unknown tokens stay visible', function () {
    $code = HtmlEmbed::resolve(htmlBlock([
        'html' => '<img src="[[asset:hero.webp]]"><img src="[[asset:missing.png]]">',
    ])['data']);

    expect($code['html'])
        ->toContain('src="'.Storage::disk('public')->url('projects/embeds/a1b2c3.webp').'"')
        ->toContain('[[asset:missing.png]]');
});

test('closing tags inside custom CSS and JS cannot end their elements early', function () {
    $code = HtmlEmbed::resolve(['css' => 'a{}</style><b>', 'js' => 'let x = "</script><b>";']);

    expect($code['css'])->not->toContain('</style')
        ->and($code['js'])->not->toContain('</script');
});

test('isolated HTML blocks render in a sandboxed frame without same-origin access', function () {
    $project = Project::factory()->create(['content' => [
        htmlBlock(['html' => '<p class="hello">Hello from the embed</p>', 'css' => '.hello{color:red}', 'js' => 'console.log(1)']),
    ]]);

    $response = $this->get(route('work.show', $project->slug))->assertOk();

    $response->assertSee('sandbox="allow-scripts allow-popups allow-popups-to-escape-sandbox allow-forms"', false)
        ->assertSee('data-embed-frame', false)
        ->assertSee(e('<p class="hello">Hello from the embed</p>'), false)
        ->assertDontSee('<p class="hello">Hello from the embed</p>', false);

    expect($response->getContent())->not->toContain('allow-same-origin');
});

test('a fixed height opts an isolated block out of auto-resizing', function () {
    $project = Project::factory()->create(['content' => [htmlBlock(['html' => '<p>Fixed</p>', 'height' => 640])]]);

    $this->get(route('work.show', $project->slug))
        ->assertOk()
        ->assertSee('style="height: 640px"', false)
        ->assertDontSee('data-embed-frame', false);
});

test('inline HTML blocks render into the page with their CSS scoped to the block', function () {
    $project = Project::factory()->create(['content' => [
        htmlBlock([
            'mode' => HtmlEmbed::MODE_INLINE,
            'html' => '<div class="badge"><img src="[[asset:hero.webp]]"></div>',
            'css' => '.badge { color: red; }',
            'js' => 'window.inlineRan = true;',
        ]),
    ]]);

    $this->get(route('work.show', $project->slug))
        ->assertOk()
        ->assertSee('<div class="badge"><img src="'.Storage::disk('public')->url('projects/embeds/a1b2c3.webp').'"></div>', false)
        ->assertSee('@scope ([data-embed="block-0"])', false)
        ->assertSee('<script>window.inlineRan = true;</script>', false);
});

test('video blocks render as a muted autoplay loop or with controls', function () {
    $project = Project::factory()->create(['content' => [
        ['type' => 'video', 'data' => ['video' => 'projects/videos/loop.mp4', 'poster' => 'projects/videos/posters/p.jpg', 'playback' => 'loop', 'width' => 'full']],
        ['type' => 'video', 'data' => ['video' => 'projects/videos/talk.webm', 'playback' => 'controls', 'width' => 'wide']],
    ]]);

    $this->get(route('work.show', $project->slug))
        ->assertOk()
        ->assertSee('autoplay muted loop data-autoplay-video', false)
        ->assertSee('type="video/mp4"', false)
        ->assertSee('poster="'.Storage::disk('public')->url('projects/videos/posters/p.jpg').'"', false)
        ->assertSee('controls', false)
        ->assertSee('type="video/webm"', false);
});

test('image blocks render with alt text and caption', function () {
    $project = Project::factory()->create(['content' => [
        ['type' => 'image', 'data' => ['image' => 'projects/blocks/shot.webp', 'alt' => 'Shelf shot', 'caption' => 'On the shelf', 'width' => 'contained']],
    ]]);

    $this->get(route('work.show', $project->slug))
        ->assertOk()
        ->assertSee('alt="Shelf shot"', false)
        ->assertSee('On the shelf');
});

test('markdown blocks still escape raw HTML', function () {
    $project = Project::factory()->create(['content' => [
        ['type' => 'markdown', 'data' => ['body' => "## Brief\n\n<script>alert(1)</script>"]],
    ]]);

    $this->get(route('work.show', $project->slug))
        ->assertOk()
        ->assertSee('<h2>Brief</h2>', false)
        ->assertDontSee('<script>alert(1)</script>', false);
});
