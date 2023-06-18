<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnprocessedData extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'method',
        'data',
        'is_processed',
        'time_period_hours',
    ];
}
