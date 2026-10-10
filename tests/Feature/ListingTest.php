<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Project;

beforeEach(function () {
    $this->withoutVite();
});

test('work search matches titles and clients', function () {
    $byTitle = Project::factory()->create(['title' => 'Morning Ritual']);
    $byClient = Project::factory()->create(['client' => 'Ritual Roasters']);
    $other = Project::factory()->create(['title' => 'Quiet Objects', 'client' => 'Fold Studio']);

    $this->get(route('work', ['q' => 'ritual']))
        ->assertOk()
        ->assertSee($byTitle->title)
        ->assertSee($byClient->title)
        ->assertDontSee($other->title)
        ->assertSee('<meta name="robots" content="noindex, follow">', false);
});

test('work can be filtered by discipline', function () {
    $branding = Project::factory()->inDiscipline('Branding')->create();
    $packaging = Project::factory()->inDiscipline('Packaging Design')->create();

    $this->get(route('work', ['discipline' => 'packaging-design']))
        ->assertOk()
        ->assertSee($packaging->title)
        ->assertDontSee($branding->title);
});

test('an unknown discipline filter is ignored', function () {
    $project = Project::factory()->inDiscipline('Branding')->create();

    $this->get(route('work', ['discipline' => 'not-a-discipline']))
        ->assertOk()
        ->assertSee($project->title);
});

test('work is paginated eight per page and keeps the filter in page links', function () {
    Project::factory()->count(9)->inDiscipline('Branding')->create();

    $this->get(route('work', ['discipline' => 'branding']))
        ->assertOk()
        ->assertSee('Showing 1–8 of 9')
        ->assertSee('discipline=branding&amp;page=2', false);

    $this->get(route('work', ['discipline' => 'branding', 'page' => 2]))
        ->assertOk()
        ->assertSee('Showing 9–9 of 9');
});

test('work listing never shows drafts', function () {
    $draft = Project::factory()->draft()->create();
    Project::factory()->create();

    $this->get(route('work'))->assertOk()->assertDontSee($draft->title);
});

test('journal can be filtered by category', function () {
    $packaging = Category::factory()->create(['name' => 'Packaging', 'slug' => 'packaging']);
    $inPackaging = Post::factory()->for($packaging)->create();
    $elsewhere = Post::factory()->create();

    $this->get(route('journal', ['category' => 'packaging']))
        ->assertOk()
        ->assertSee($inPackaging->title)
        ->assertDontSee($elsewhere->title);
});

test('journal search matches article content', function () {
    $match = Post::factory()->create(['body' => 'We print every dieline at full size.']);
    $other = Post::factory()->create(['body' => 'Nothing relevant here.']);

    $this->get(route('journal', ['q' => 'dieline']))
        ->assertOk()
        ->assertSee($match->title)
        ->assertDontSee($other->title);
});

test('journal is paginated nine per page, newest first', function () {
    $posts = Post::factory()
        ->count(10)
        ->sequence(fn ($sequence) => ['published_at' => now()->subDays($sequence->index + 1)])
        ->create();

    $this->get(route('journal'))
        ->assertOk()
        ->assertSee('Showing 1–9 of 10')
        ->assertSee($posts->first()->title)
        ->assertDontSee($posts->last()->title);

    $this->get(route('journal', ['page' => 2]))
        ->assertOk()
        ->assertSee($posts->last()->title);
});

test('listings with no matches explain that and offer a way back', function () {
    Post::factory()->create();

    $this->get(route('journal', ['q' => 'zzzz-no-match']))
        ->assertOk()
        ->assertSee('No articles match that search.');
});
