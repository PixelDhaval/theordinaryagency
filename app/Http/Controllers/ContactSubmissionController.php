<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactSubmissionRequest;
use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;

class ContactSubmissionController extends Controller
{
    public function store(StoreContactSubmissionRequest $request): RedirectResponse
    {
        ContactSubmission::create([
            ...$request->safe()->only(['name', 'email', 'company', 'service', 'message']),
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->to(route('contact').'#enquiry')
            ->with('contact_status', 'Thanks - your message is in. We’ll get back to you shortly.');
    }
}
