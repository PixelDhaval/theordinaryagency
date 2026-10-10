<?php

use App\Models\Post;
use App\Models\Project;
use App\Support\ServiceCatalog;

beforeEach(function () {
    $this->withoutVite();
});

test('pages include canonical, open graph and organization schema', function () {
    $this->get(route('services'))
        ->assertOk()
        ->assertSee('<link rel="canonical" href="'.route('services').'">', false)
        ->assertSee('<meta property="og:url" content="'.route('services').'">', false)
        ->assertSee('<meta name="twitter:card" content="summary_large_image">', false)
        ->assertSee('"@type":"Organization"', false);
});

test('inner pages render breadcrumbs with matching schema', function () {
    $service = ServiceCatalog::find('branding');

    $this->get(route('services.show', $service->slug))
        ->assertOk()
        ->assertSee('aria-label="Breadcrumb"', false)
        ->assertSee('"@type":"BreadcrumbList"', false)
        ->assertSee('"item":"'.route('services').'"', false)
        ->assertSee('"@type":"Service"', false)
        ->assertSee('"@type":"FAQPage"', false);
});

test('pages are noindex outside production and indexable in production', function () {
    $this->get(route('about'))->assertSee('<meta name="robots" content="noindex, nofollow">', false);

    $this->app['env'] = 'production';

    $this->get(route('about'))->assertSee('<meta name="robots" content="index, follow, max-image-preview:large">', false);
});

test('sitemap lists every page and service', function () {
    $response = $this->get(route('sitemap'))
        ->assertOk()
        ->assertHeader('Content-Type', 'application/xml');

    collect(['home', 'services', 'work', 'process', 'about', 'journal', 'contact'])
        ->each(fn (string $route) => $response->assertSee('<loc>'.route($route).'</loc>', false));

    ServiceCatalog::all()->each(
        fn ($service) => $response->assertSee('<loc>'.route('services.show', $service->slug).'</loc>', false)
    );
});

test('robots.txt blocks crawlers outside production and points to the sitemap in production', function () {
    $this->get(route('robots'))->assertOk()->assertSee('Disallow: /');

    $this->app['env'] = 'production';

    $this->get(route('robots'))
        ->assertOk()
        ->assertSee('Allow: /')
        ->assertSee('Sitemap: '.route('sitemap'));
});

test('admin SEO overrides replace the default meta for an article', function () {
    $post = Post::factory()->create([
        'meta_title' => 'Custom SEO title',
        'meta_description' => 'Custom SEO description.',
        'canonical_url' => 'https://example.com/original-article',
    ]);

    $this->get(route('journal.show', $post->slug))
        ->assertOk()
        ->assertSee('<title>Custom SEO title</title>', false)
        ->assertSee('<meta name="description" content="Custom SEO description.">', false)
        ->assertSee('<link rel="canonical" href="https://example.com/original-article">', false);
});

test('a noindexed case study tells crawlers not to index it', function () {
    $project = Project::factory()->noindex()->create();

    $this->get(route('work.show', $project->slug))
        ->assertOk()
        ->assertSee('<meta name="robots" content="noindex, follow">', false);
});

test('sitemap includes published entries and leaves out drafts and noindexed ones', function () {
    $published = Post::factory()->create();
    $draft = Post::factory()->draft()->create();
    $hidden = Project::factory()->noindex()->create();
    $project = Project::factory()->create();

    $this->get(route('sitemap'))
        ->assertOk()
        ->assertSee(route('journal.show', $published->slug))
        ->assertSee(route('work.show', $project->slug))
        ->assertDontSee(route('journal.show', $draft->slug))
        ->assertDontSee(route('work.show', $hidden->slug));
});
