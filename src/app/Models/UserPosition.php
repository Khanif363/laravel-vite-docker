<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPosition extends Model
{
    protected $connection = 'mysql3';
    protected $table = 'r_jabatan';

    protected $guarded = [];

    use HasFactory;
}
