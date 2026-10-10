<?php

use App\Models\Post;
use App\Models\Project;
use App\Support\ServiceCatalog;

beforeEach(function () {
    $this->withoutVite();
});

test('static pages render with their meta title', function (string $route, string $title) {
    $this->get(route($route))
        ->assertOk()
        ->assertSee('<title>'.e($title).'</title>', false);
})->with([
    'about' => ['about', 'About Us | The Ordinary Agency Design Studio'],
    'services' => ['services', 'Design Services | Branding, Packaging, UI/UX & More'],
    'work' => ['work', 'Our Work | Branding, Packaging & UI/UX Case Studies'],
    'process' => ['process', 'Our Process | How We Work | The Ordinary Agency'],
    'journal' => ['journal', 'Journal | Design Insights from The Ordinary Agency'],
    'contact' => ['contact', 'Contact Us | Book a Call | The Ordinary Agency'],
]);

test('every service page renders its own copy', function () {
    ServiceCatalog::all()->each(function ($service) {
        $this->get(route('services.show', $service->slug))
            ->assertOk()
            ->assertSee('<title>'.e($service->meta_title).'</title>', false)
            ->assertSee($service->intro);
    });
});

test('service pages list published work in that discipline', function () {
    $branding = Project::factory()->inDiscipline('Branding')->create();
    $packaging = Project::factory()->inDiscipline('Packaging Design')->create();

    $this->get(route('services.show', 'branding'))
        ->assertOk()
        ->assertSee($branding->title)
        ->assertDontSee($packaging->title);
});

test('unknown slugs return the custom 404 page', function (string $url) {
    $this->get($url)
        ->assertNotFound()
        ->assertSee('Nothing to see')
        ->assertSee('<meta name="robots" content="noindex, follow">', false);
})->with([
    'service' => '/services/not-a-service',
    'project' => '/work/not-a-project',
    'post' => '/journal/not-a-post',
    'page' => '/definitely-not-a-page',
]);

test('published case studies and articles render', function () {
    $project = Project::factory()->create();
    $post = Post::factory()->create();

    $this->get(route('work.show', $project->slug))
        ->assertOk()
        ->assertSee($project->title)
        ->assertSee($project->client);

    $this->get(route('journal.show', $post->slug))
        ->assertOk()
        ->assertSee($post->title)
        ->assertSee($post->category->name)
        ->assertSee('"@type":"BlogPosting"', false);
});

test('drafts and scheduled entries are not publicly reachable', function () {
    $this->get(route('work.show', Project::factory()->draft()->create()->slug))->assertNotFound();
    $this->get(route('journal.show', Post::factory()->draft()->create()->slug))->assertNotFound();
    $this->get(route('journal.show', Post::factory()->scheduled()->create()->slug))->assertNotFound();
});

test('article markdown is rendered with raw HTML escaped', function () {
    $post = Post::factory()->create(['body' => "## Heading\n\n<script>alert(1)</script>"]);

    $this->get(route('journal.show', $post->slug))
        ->assertOk()
        ->assertSee('<h2>Heading</h2>', false)
        ->assertDontSee('<script>alert(1)</script>', false);
});
