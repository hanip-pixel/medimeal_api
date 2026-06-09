<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealPackage extends Model
{
    protected $fillable = ['name', 'description', 'medical_condition', 'price', 'duration_days', 'calories', 'image_url', 'is_available'];

    public function orders() {
        return $this->hasMany(Order::class);
    }
}