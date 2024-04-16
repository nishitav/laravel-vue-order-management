<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    
    protected $table = 'customer';
    
    protected $fillable = [
        'name', 'email', 'phone'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    public function address()
    {
        return $this->hasOne(Address::class);
    }
}
