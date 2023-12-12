<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $table = 'log_activities';

    protected $guarded = [];
}
