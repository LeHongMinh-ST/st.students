<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function index(): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('viewAny', Post::class);

        return view('pages.post.index');
    }

    public function create(): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('create', Post::class);

        return view('pages.post.create');
    }

    public function edit(Post $post): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('update', $post);

        return view('pages.post.edit', compact('post'));
    }

    public function show(Post $post): View|Application|Factory|RedirectResponse
    {
        Gate::authorize('view', $post);

        return view('pages.post.show', compact('post'));
    }
}
