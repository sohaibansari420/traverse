<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class UserWallet extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "user_wallets";

    protected $fillable = ['user_id','wallet_id','balance','status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
