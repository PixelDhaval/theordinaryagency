<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('home', [
            'projects' => Project::published()->curated()->take(4)->get(),
            'posts' => Post::published()->with('category')->latest('published_at')->take(3)->get(),
        ]);
    }
}
