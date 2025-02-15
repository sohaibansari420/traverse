<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $guarded = ['id'];
    protected $table = "plans";

    public function commissionDetail()
    {
        return $this->hasMany(CommissionDetail::class);
    }
    public function purchasedPlan()
    {
        return $this->hasMany(PurchasedPlan::class)->orderBy('id','desc');
    }
}
