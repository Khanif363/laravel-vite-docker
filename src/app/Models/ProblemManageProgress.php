<?php

namespace App\Models;

use App\Models\User;
use App\Models\Attachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProblemManageProgress extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function attachments() {
        return $this->hasMany(Attachment::class);
    }

    public function inputer() {
        return $this->belongsTo(User::class);
    }
}
