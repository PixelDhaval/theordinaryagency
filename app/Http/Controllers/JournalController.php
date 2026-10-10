<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JournalController extends Controller
{
    private const PER_PAGE = 9;

    public function index(Request $request): View
    {
        $search = Str::limit(trim($request->string('q')->toString()), 100, '');

        $categories = Category::whereHas('posts', fn (Builder $query) => $query->published())
            ->orderBy('name')
            ->get();

        // Only existing category slugs reach the query; anything else is ignored.
        $activeCategory = $categories->firstWhere('slug', $request->string('category')->toString());

        $posts = Post::published()
            ->with('category')
            ->search($search)
            ->when($activeCategory, fn ($query, Category $category) => $query->whereBelongsTo($category))
            ->latest('published_at')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('journal.index', [
            'posts' => $posts,
            'categories' => $categories,
            'search' => $search,
            'activeCategory' => $activeCategory,
            'hasAnyPosts' => $posts->total() > 0 || Post::published()->exists(),
        ]);
    }

    public function show(string $slug): View
    {
        $post = Post::published()->with('category')->where('slug', $slug)->firstOrFail();

        $relatedPosts = Post::published()
            ->with('category')
            ->whereKeyNot($post->getKey())
            ->when($post->category_id, fn ($query, int $categoryId) => $query->orderByRaw('category_id = ? desc', [$categoryId]))
            ->latest('published_at')
            ->take(2)
            ->get();

        return view('journal.show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
        ]);
    }
}
