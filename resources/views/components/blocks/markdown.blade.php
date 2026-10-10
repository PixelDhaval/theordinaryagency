<div data-reveal class="prose-agency">
    {!! Str::markdown((string) ($data['body'] ?? ''), ['html_input' => 'escape', 'allow_unsafe_links' => false]) !!}
</div>
