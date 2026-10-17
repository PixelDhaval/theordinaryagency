<?php

namespace App\Http\Requests;

use App\Support\ServiceCatalog;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactSubmissionRequest extends FormRequest
{
    /** Public form: anyone may submit. Abuse is handled by the throttle and the honeypot. */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'company' => ['nullable', 'string', 'max:160'],
            'service' => ['nullable', 'string', Rule::in(ServiceCatalog::all()->pluck('name'))],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            // Honeypot: hidden from people, filled by naive bots.
            'website' => ['prohibited'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'message.min' => 'Tell us a little more about your project - at least a sentence or two.',
            'website.prohibited' => 'Something went wrong. Please try again.',
        ];
    }
}
