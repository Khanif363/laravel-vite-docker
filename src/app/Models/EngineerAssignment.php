<?php

namespace App\Models;

use App\Models\Engineer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EngineerAssignment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function engineer() {
        return $this->hasMany(Engineer::class);
    }
}
