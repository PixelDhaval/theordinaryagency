<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use App\Support\ServiceCatalog;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = collect(['home', 'services', 'work', 'process', 'about', 'journal', 'contact'])
            ->map(fn (string $route) => ['loc' => route($route), 'lastmod' => null])
            ->merge(ServiceCatalog::all()->map(fn ($service) => [
                'loc' => route('services.show', $service->slug),
                'lastmod' => null,
            ]))
            ->merge(Project::published()->where('noindex', false)->get(['slug', 'updated_at'])->map(fn (Project $project) => [
                'loc' => route('work.show', $project->slug),
                'lastmod' => $project->updated_at?->toDateString(),
            ]))
            ->merge(Post::published()->where('noindex', false)->get(['slug', 'updated_at'])->map(fn (Post $post) => [
                'loc' => route('journal.show', $post->slug),
                'lastmod' => $post->updated_at?->toDateString(),
            ]));

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }

    public function robots(): Response
    {
        $lines = app()->isProduction()
            ? ['User-agent: *', 'Allow: /', 'Disallow: /admin', '', 'Sitemap: '.route('sitemap')]
            : ['User-agent: *', 'Disallow: /'];

        return response(implode("\n", $lines)."\n")->header('Content-Type', 'text/plain');
    }
}
