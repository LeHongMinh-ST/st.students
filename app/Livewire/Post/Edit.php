<?php

declare(strict_types=1);

namespace App\Livewire\Post;

use App\Enums\PostStatus;
use App\Helpers\LogActivityHelper;
use App\Models\Post;
use App\Models\PostNotification;
use App\Models\User;
use App\Services\SsoService;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    public Post $post;

    #[Validate(as: 'tiêu đề')]
    public string $title = '';

    #[Validate(as: 'nội dung')]
    public string $content = '';

    #[Validate(as: 'trạng thái')]
    public string $status = '';

    public bool $wasPublished = false;

    public function mount(Post $post): void
    {
        $this->post = $post;
        $this->title = $post->title;
        $this->content = $post->content;
        $this->status = $post->status->value;
        $this->wasPublished = PostStatus::PUBLISH === $post->status;
    }

    public function render()
    {
        return view('livewire.post.edit');
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

        $isNewlyPublished = !$this->wasPublished && $this->status === PostStatus::PUBLISH->value;

        $this->post->update([
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status,
        ]);

        // Log the successful post update
        LogActivityHelper::create(
            'Cập nhật bài viết',
            'Cập nhật bài viết: ' . $this->post->title
        );

        // If the post is being published for the first time, create notifications
        if ($isNewlyPublished) {
            $this->createNotifications();
        }

        session()->flash('success', 'Bài viết đã được cập nhật thành công.');
        $this->redirect(route('posts.index'));
    }

    public function delete(): void
    {
        $postTitle = $this->post->title;

        $this->post->delete();

        // Log the successful post deletion
        LogActivityHelper::create(
            'Xóa bài viết',
            'Xóa bài viết: ' . $postTitle
        );

        session()->flash('success', 'Bài viết đã được xóa thành công.');
        $this->redirect(route('posts.index'));
    }

    /**
     * Create notifications for all users in the same faculty when a post is published
     */
    private function createNotifications(): void
    {
        $facultyId = app(SsoService::class)->getFacultyId();

        // Get all users in the same faculty
        $users = User::where('faculty_id', $facultyId)
            ->whereHas('userRoles', function ($query): void {
                $query->whereHas('permissions', function ($q): void {
                    $q->where('code', 'post.index');
                });
            })
            ->get();

        foreach ($users as $user) {
            PostNotification::create([
                'post_id' => $this->post->id,
                'user_id' => $user->id,
                'faculty_id' => $facultyId,
                'title' => 'Bài viết mới: ' . $this->title,
                'content' => 'Một bài viết mới đã được đăng: ' . $this->title,
                'read' => false,
            ]);
        }
    }
}
