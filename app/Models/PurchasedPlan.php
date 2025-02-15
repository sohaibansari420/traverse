<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasedPlan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "purchased_plans";
    protected $fillable = [
        'user_id',
        'plan_id',
        'type',
        'trx',
        'compounding',
        'amount',
        'limit_consumed',
        'roi_return',
        'is_expired',
        'is_roi',
        'with_point',
        'auto_renew',
        'auto_compounding',
        'plan_limit',
    ];
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
