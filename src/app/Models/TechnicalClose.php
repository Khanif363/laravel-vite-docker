<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalClose extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dates = ['close_datetime'];
}
