<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronUpdate extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "cron_updates";
    protected $fillable = [
        'user_id','type', 'amount', 'details','status'
    ];
}
