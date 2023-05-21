<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "commissions";
    protected $fillable = ['id','name','wallet_id','is_compounding','is_package','status'];
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
    public function commissionDetail()
    {
        return $this->hasMany(CommissionDetail::class);
    }
    public function commissionRelease()
    {
        return $this->belongsTo(CommissionRelease::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('id','desc');
    }
}
