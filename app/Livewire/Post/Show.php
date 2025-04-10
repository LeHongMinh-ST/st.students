<?php

declare(strict_types=1);

namespace App\Livewire\Post;

use App\Models\Post;
use Livewire\Component;

class Show extends Component
{
    public Post $post;

    public function mount(Post $post): void
    {
        $this->post = $post;
    }

    public function render()
    {
        return view('livewire.post.show');
    }
}
