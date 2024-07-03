<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;

class RenderCategory extends Component
{
    public $categories = '';

    public function mount(){
        $this->loadCategories();
    }

    public function loadCategories(){
        $this->categories = Category::all();
    }
    public function render()
    {
        return view('livewire.render-category');
    }
}
