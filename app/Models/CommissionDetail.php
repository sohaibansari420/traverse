<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionDetail extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "commission_details";
    protected $fillable = ['id','commission_id', 'commission_release_id','plan_id','level','percent', 'commission_limit', 'capping', 'days', 'direct'];
    public function commission()
    {
        return $this->belongsTo(Commission::class);
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
