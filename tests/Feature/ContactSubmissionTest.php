<?php

use App\Models\ContactSubmission;

beforeEach(function () {
    $this->withoutVite();
});

test('a valid enquiry is stored and the visitor sees a confirmation', function () {
    $this->post(route('contact.store'), [
        'name' => 'Ada Lovelace',
        'email' => 'ada@example.com',
        'company' => 'Analytical Engines',
        'service' => 'Branding',
        'message' => 'We are launching a new product line and need an identity.',
    ])
        ->assertRedirect(route('contact').'#enquiry')
        ->assertSessionHas('contact_status');

    $submission = ContactSubmission::sole();

    expect($submission)
        ->name->toBe('Ada Lovelace')
        ->email->toBe('ada@example.com')
        ->service->toBe('Branding')
        ->read_at->toBeNull()
        ->ip_address->not->toBeNull();
});

test('name, email and message are required', function () {
    $this->post(route('contact.store'), [])
        ->assertSessionHasErrors(['name', 'email', 'message']);

    expect(ContactSubmission::count())->toBe(0);
});

test('a too-short message gets a helpful error', function () {
    $this->post(route('contact.store'), [
        'name' => 'Ada',
        'email' => 'ada@example.com',
        'message' => 'Hi',
    ])->assertSessionHasErrors(['message' => 'Tell us a little more about your project — at least a sentence or two.']);
});

test('the service must be one the studio offers', function () {
    $this->post(route('contact.store'), [
        'name' => 'Ada',
        'email' => 'ada@example.com',
        'service' => 'Accounting',
        'message' => 'Can you help with our books please?',
    ])->assertSessionHasErrors('service');
});

test('submissions that fill the honeypot are rejected', function () {
    $this->post(route('contact.store'), [
        'name' => 'Bot',
        'email' => 'bot@example.com',
        'message' => 'Buy cheap things at my website today.',
        'website' => 'https://spam.example',
    ])->assertSessionHasErrors('website');

    expect(ContactSubmission::count())->toBe(0);
});

test('the contact form is rate limited', function () {
    $payload = [
        'name' => 'Ada',
        'email' => 'ada@example.com',
        'message' => 'Following up on my earlier message.',
    ];

    foreach (range(1, 3) as $attempt) {
        $this->post(route('contact.store'), $payload)->assertRedirect();
    }

    $this->post(route('contact.store'), $payload)->assertTooManyRequests();
    expect(ContactSubmission::count())->toBe(3);
});
