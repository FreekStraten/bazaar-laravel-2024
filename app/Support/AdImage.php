<?php

namespace App\Support;

use App\Models\Ad;

class AdImage
{
    public static function coverUrlFor(Ad $ad): string
    {
        if (!empty($ad->image) && file_exists(public_path('ads-images/'.$ad->image)))
        {
            return asset('ads-images/'.$ad->image);
        }
        return asset('images/product-placeholder.png');
    }
}
