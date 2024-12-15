<?php

namespace App\Livewire\News;

use Livewire\Component;

class AddNewsPage extends Component
{
    public $avatar;
    public $saved_avatar;
    public function render()
    {
        return view('livewire.news.add-news-page');
    }
}
