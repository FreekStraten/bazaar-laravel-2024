<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalAd extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address_id',
        'title',
        'description',
        'price',
        'image',
        'created_at',
        'updated_at',
    ];

    public function userFavorites()
    {
        return $this->hasMany(UserRentalAdFavorite::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }


}
