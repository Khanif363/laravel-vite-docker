<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MantoolsDatacom extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'datacom';

    // protected $guarded = [];
}
