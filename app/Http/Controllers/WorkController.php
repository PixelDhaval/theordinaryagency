<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Support\ServiceCatalog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WorkController extends Controller
{
    private const PER_PAGE = 8;

    public function index(Request $request): View
    {
        $search = Str::limit(trim($request->string('q')->toString()), 100, '');

        $disciplines = ServiceCatalog::all()
            ->pluck('name')
            ->intersect(Project::published()->distinct()->pluck('discipline'))
            ->mapWithKeys(fn (string $name) => [Str::slug($name) => $name]);

        // Only allow-listed discipline slugs reach the query; anything else is ignored.
        $activeDiscipline = $disciplines->get($request->string('discipline')->toString());

        $projects = Project::published()
            ->search($search)
            ->when($activeDiscipline, fn ($query, string $discipline) => $query->where('discipline', $discipline))
            ->curated()
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('work.index', [
            'projects' => $projects,
            'disciplines' => $disciplines,
            'search' => $search,
            'activeDiscipline' => $activeDiscipline,
            'hasAnyProjects' => $projects->total() > 0 || Project::published()->exists(),
        ]);
    }

    public function show(string $slug): View
    {
        $project = Project::published()->where('slug', $slug)->firstOrFail();

        $slugs = Project::published()->curated()->pluck('slug');
        $nextSlug = $slugs->count() > 1 ? $slugs[($slugs->search($project->slug) + 1) % $slugs->count()] : null;

        return view('work.show', [
            'project' => $project,
            'nextProject' => $nextSlug ? Project::published()->where('slug', $nextSlug)->first() : null,
        ]);
    }
}
