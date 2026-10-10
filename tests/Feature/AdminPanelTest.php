<?php

use App\Filament\Resources\ContactSubmissions\ContactSubmissionResource;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\ContactSubmission;
use App\Models\Post;
use App\Models\Project;
use App\Models\User;

test('guests are sent to the admin login', function () {
    $this->get(PostResource::getUrl('index'))->assertRedirect(route('filament.admin.auth.login'));
});

test('staff can open every admin section', function (string $resource) {
    $this->actingAs(User::factory()->create())
        ->get($resource::getUrl('index'))
        ->assertOk();
})->with([
    'work' => ProjectResource::class,
    'journal' => PostResource::class,
    'enquiries' => ContactSubmissionResource::class,
    'team' => UserResource::class,
]);

test('staff can open the edit forms for work and articles', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(ProjectResource::getUrl('edit', ['record' => Project::factory()->create()]))
        ->assertOk()
        ->assertSee('Meta title');

    $this->actingAs($user)
        ->get(PostResource::getUrl('edit', ['record' => Post::factory()->create()]))
        ->assertOk()
        ->assertSee('Hide from search engines');
});

test('opening an enquiry marks it as read', function () {
    $submission = ContactSubmission::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(ContactSubmissionResource::getUrl('view', ['record' => $submission]))
        ->assertOk()
        ->assertSee($submission->message);

    expect($submission->fresh()->read_at)->not->toBeNull();
});

test('enquiries cannot be created from the admin', function () {
    expect(ContactSubmissionResource::canCreate())->toBeFalse()
        ->and(ContactSubmissionResource::hasPage('create'))->toBeFalse()
        ->and(ContactSubmissionResource::hasPage('edit'))->toBeFalse();
});
