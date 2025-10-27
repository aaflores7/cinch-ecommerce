<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = []; // Allow mass assignment for all fields

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
