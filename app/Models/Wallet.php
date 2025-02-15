<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "wallets";

    protected $fillable = ['name','currency', 'icon','status', 'display'];

    public function userWallet()
    {
        return $this->hasMany(UserWallet::class);
    }
    public function commission()
    {
        return $this->hasMany(Commission::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('id','desc');
    }
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class)->orderBy('id','desc');
    }
}
