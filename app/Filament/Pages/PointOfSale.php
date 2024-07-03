<?php

namespace App\Filament\Pages;

use Illuminate\Contracts\Support\Htmlable;
use Filament\Pages\Page;
use App\Models\Category;
use App\Models\Product;
use App\Models\Settings;

class PointOfSale extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 4;
    protected static string $view = 'filament.pages.point-of-sale';

    public $categories = '';
    public $products = '';
    public $settings = null;
    public $products_in_cart = [];
    public $subtotal = 0;
    public $discount = 0;
    public $grand_total = 0;
    public $vat_amount=0;

    public function getTitle(): string|Htmlable
    {
        return '';
    }

    public function mount()
    {
        $this->loadCategories();
        $this->loadProducts();
        $this->loadSettings();
        $this->updateTotals();

    }

    public function loadCategories()
    {
        $this->categories = Category::all();
    }

    public function loadProducts()
    {
        $this->products = Product::all();
    }

    public function loadSettings()
    {
        $this->settings = Settings::first();
    }

    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);
    
        $exists = false;
        foreach ($this->products_in_cart as &$item) {
            if ($item['id'] == $product->id) {
                $item['quantity']++;
                $exists = true;
                break;
            }
        }
    
        if (!$exists) {
            $this->products_in_cart[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
            ];
        }
    
        $this->updateTotals();
    }
    

    public function removeFromCart($productId)
    {
        foreach ($this->products_in_cart as $key => $item) {
            if ($item['id'] == $productId) {
                unset($this->products_in_cart[$key]);
                $this->updateTotals();
                break;
            }
        }
    }

    public function updateTotals()
    {
        $this->subtotal = 0;
        foreach ($this->products_in_cart as $item) {
            $this->subtotal += $item['price'] * $item['quantity'];
        }
        $vat = 0;
        if($this->settings && $this->settings->charge_tax === 1){
            $vat = $this->subtotal * ($this->settings->tax_percentage / 100);
        }

        $discount = is_numeric($this->discount) ? $this->discount : 0;

        $this->vat_amount = $vat;
        $this->grand_total = $this->subtotal - ($this->subtotal * ($discount / 100)) + $this->vat_amount;

    }

    public function updateDiscount()
    {
        $this->updateTotals();
    }

    public function holdOrder()
    {
        // Handle hold order functionality here
    }

    public function payOrder()
    {
        // Handle pay order functionality here
    }
}
