<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'barcode',
        'price',
        'stock',
        'image',
        'stock_check',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
