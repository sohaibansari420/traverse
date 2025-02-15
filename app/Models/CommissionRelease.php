<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionRelease extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "commission_releases";
    public function commission()
    {
        return $this->hasMany(Commission::class);
    }
}
