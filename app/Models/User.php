<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address_id',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /** Ads die deze user heeft geplaatst (alle typen) */
    public function ads()
    {
        return $this->hasMany(Ad::class);
    }

    /** Alleen verhuur-advertenties van deze user (als ads.is_rental = true) */
    public function rentalAds()
    {
        return $this->hasMany(Ad::class)->where('is_rental', true);
    }

    /** Biedingen die deze user HEEFT GEDAAN */
    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    /** Biedingen OP advertenties van deze user (handig voor accept-flow) */
    public function receivedBids()
    {
        // via Ad (ads.user_id -> bids.ad_id)
        return $this->hasManyThrough(
            Bid::class,      // eindmodel
            Ad::class,       // tussenmodel
            'user_id',       // Foreign key op ads die naar users wijst
            'ad_id',         // Foreign key op bids die naar ads wijst
            'id',            // Local key op users
            'id'             // Local key op ads
        );
    }

    /** Favoriete ads van deze user (pivot: user_ad_favorites) */
    public function favorites()
    {
        return $this->belongsToMany(Ad::class, 'user_ad_favorites', 'user_id', 'ad_id');
    }

    // Favorieten worden centraal beheerd via de pivot-relatie `favorites()`.

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function reviews()
    {
        return $this->hasMany(UserReview::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function permissions()
    {
        return $this->role->permissions;
    }

    public function hasPermission($permission)
    {
        $userPermissions = $this->permissions()->pluck('name')->toArray();
        return in_array($permission, $userPermissions);
    }
}
