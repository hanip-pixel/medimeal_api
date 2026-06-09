<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['order_code', 'user_id', 'package_id', 'quantity', 'total_price', 'status', 'delivery_address'];

    protected $casts = [
        'status' => 'string',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function package() {
        return $this->belongsTo(MealPackage::class, 'package_id');
    }
}