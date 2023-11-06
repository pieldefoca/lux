<?php

namespace Pieldefoca\Lux\Livewire\Blog\Posts;

use Pieldefoca\Lux\Livewire\LuxComponent;
use Pieldefoca\Lux\Models\Post;

class Edit extends LuxComponent
{
    public Post $post;

    public function render()
    {
        return view('lux::livewire.blog.posts.edit');
    }
}
