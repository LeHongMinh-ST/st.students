<?php

declare(strict_types=1);

namespace App\Livewire\Post;

use App\Helpers\Constants;
use App\Models\Post;
use App\Services\SsoService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    public function render()
    {
        $facultyId = app(SsoService::class)->getFacultyId();

        $posts = Post::query()
            ->with('user')
            ->where('faculty_id', $facultyId)
            ->search($this->search)
            ->orderBy('created_at', 'desc')
            ->paginate(Constants::PER_PAGE);

        return view('livewire.post.index', [
            'posts' => $posts
        ]);
    }

    public function placeholder()
    {
        return view('components.placeholders.table-placeholder');
    }
}
