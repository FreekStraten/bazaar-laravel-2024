<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'street',
        'house_number',
        'city',
        'zip_code',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

}
