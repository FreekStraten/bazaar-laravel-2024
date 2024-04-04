<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Pivot
{
    use HasFactory;

    protected $table = 'permission_role';
    public $timestamps = false;
    protected $fillable = ['permission_id', 'role_id'];


}
