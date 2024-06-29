<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Product;
use App\Models\Settings;

class ShowProducts extends Component
{
    public $categories = '', $products='', $settings = '';


    public function mount(){
        $this->loadCategories();
        $this->loadProducts();
        $this->loadSettings();
    }

    public function render()
    {
        return view('livewire.show-products');
    }
    public function loadCategories(){
        $this->categories = Category::all();
    }
    public function loadProducts(){
        $this->products = Product::all();
    }
    public function loadSettings(){
        $this->settings = Settings::first();
    }

}
