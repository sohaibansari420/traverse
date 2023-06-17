<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    /**
     * Get the model display name
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    // public function data() : Attribute
    // {
    //     dd($this);
    //     return Attribute::get(fn () => json_decode($this->data));
    // }
}
