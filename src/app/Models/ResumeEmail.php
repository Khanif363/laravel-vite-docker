<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumeEmail extends Model
{
    use HasFactory;

    protected $guarded = [];

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';


    protected $dates = ['created_date', 'updated_date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'inputer_id');
    }
}
