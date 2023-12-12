<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Approval extends Model
{
    use HasFactory;

    protected $guarded = [];

    const CREATED_AT = 'inputed_date';
    const UPDATED_AT = 'updated_date';


    protected $dates = ['inputed_date', 'updated_date'];

    public function approvalable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'inputer_id');
    }
}
