<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Support\ServiceCatalog;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('services.index', [
            'services' => ServiceCatalog::all(),
        ]);
    }

    public function show(string $slug): View
    {
        $service = ServiceCatalog::find($slug) ?? abort(404);

        return view('services.show', [
            'service' => $service,
            'related' => ServiceCatalog::find($service->related['slug']),
            'otherServices' => ServiceCatalog::all()->reject(fn ($other) => $other->slug === $service->slug),
            'projects' => Project::published()
                ->where(fn (Builder $query) => $query
                    ->where('discipline', $service->name)
                    ->orWhereJsonContains('services', $service->name))
                ->curated()
                ->take(2)
                ->get(),
        ]);
    }
}
