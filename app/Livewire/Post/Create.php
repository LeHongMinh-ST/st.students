<?php

declare(strict_types=1);

namespace App\Livewire\Post;

use App\Enums\PostStatus;
use App\Helpers\LogActivityHelper;
use App\Models\Post;
use App\Services\SsoService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    #[Validate(as: 'tiêu đề')]
    public string $title = '';

    #[Validate(as: 'nội dung')]
    public string $content = '';

    #[Validate(as: 'trạng thái')]
    public string $status = '';

    public function mount(): void
    {
        $this->status = PostStatus::DRAFT->value;
    }

    public function render()
    {
        return view('livewire.post.create');
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => ['required', new Enum(PostStatus::class)],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $facultyId = app(SsoService::class)->getFacultyId();

        $post = Post::create([
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status,
            'faculty_id' => $facultyId,
            'user_id' => Auth::id(),
        ]);

        // Log the successful post creation
        LogActivityHelper::create(
            'Tạo bài viết',
            'Tạo bài viết mới: ' . $post->title
        );

        session()->flash('success', 'Bài viết đã được tạo thành công.');
        $this->redirect(route('posts.index'));
    }
}
