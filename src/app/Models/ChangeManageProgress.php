<?php

namespace App\Models;

use App\Models\User;
use App\Models\Approval;
use App\Models\Attachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChangeManageProgress extends Model
{
    use HasFactory;

    const CREATED_AT = 'inputed_date';
    const UPDATED_AT = 'updated_date';

    protected $guarded = [];

    protected $casts = [
        'edited' => 'json',
    ];

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'change_manage_progress_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'inputer_id');
    }

    public function approval(): MorphOne
    {
        return $this->morphOne(Approval::class, 'approvalable');
    }
}
