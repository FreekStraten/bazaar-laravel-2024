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
        $path = $this->image_path ?? '';
        if ($path) {
            $thumb = str_replace('products/orig/', 'products/thumbs/', $path);
            if (Storage::disk('public')->exists($thumb)) {
                return Storage::url($thumb);
            }
            if (Storage::disk('public')->exists($path)) {
                return Storage::url($path);
            }
        }
        return asset('images/product-placeholder.png');
    }

    public function getIsSoldAttribute(): bool
    {
        if ((bool) $this->is_rental) {
            return false;
        }

        if ($this->relationLoaded('bids')) {
            return (bool) $this->bids->firstWhere('is_accepted', true);
        }

        return $this->bids()->where('is_accepted', true)->exists();
    }


}
