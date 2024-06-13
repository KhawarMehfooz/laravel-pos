<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Settings extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'store_name',
        'currency',
        'charge_tax',
        'tax_percentage',
        'contact_info',
        'location',
        'store_logo',
        'receipt_footer'
    ];

    public function user(){
        return $this->belongTo(User::class);
    }
}
