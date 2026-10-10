<?php

use App\Models\Post;
use App\Models\Project;

beforeEach(function () {
    $this->withoutVite();
});

test('home page shows published work and journal entries', function () {
    $project = Project::factory()->create();
    $post = Post::factory()->create();

    $this->get(route('home'))
        ->assertOk()
        ->assertSee($project->title)
        ->assertSee(route('work.show', $project->slug))
        ->assertSee($post->title)
        ->assertSee(route('journal.show', $post->slug));
});

test('home page leaves out drafts and scheduled entries', function () {
    $draftProject = Project::factory()->draft()->create();
    $draftPost = Post::factory()->draft()->create();
    $scheduledPost = Post::factory()->scheduled()->create();

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee($draftProject->title)
        ->assertDontSee($draftPost->title)
        ->assertDontSee($scheduledPost->title);
});

test('home page hides the work and journal sections when nothing is published', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('id="work"', false)
        ->assertDontSee('id="journal"', false);
});

test('a featured project makes the home selection ahead of higher-sorted projects', function () {
    $regular = Project::factory()
        ->count(4)
        ->sequence(fn ($sequence) => ['sort_order' => $sequence->index])
        ->create();
    $featured = Project::factory()->featured()->create(['sort_order' => 99]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee($featured->title)
        ->assertDontSee($regular->last()->title);
});
