<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class Ad extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address_id',
        'title',
        'description',
        'price',
        'image_path',
        'is_rental',
        'created_at',
        'updated_at',
        'qr_code'
    ];

    public function userFavorites()
    {
        return $this->hasMany(UserAdFavorite::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function reviews()
    {
        return $this->hasMany(AdReview::class);
    }

    public function getCoverUrlAttribute(): string
    {
        if ($this->image_path && \Storage::disk('public')->exists($this->image_path)) {
            return Storage::url($this->image_path); // bv. /storage/products/thumbs/abc.jpg
        }
        return asset('images/product-placeholder.png');
    }

}
