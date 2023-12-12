<?php

namespace App\Models;

use App\Models\User;
use App\Models\Attachment;
use App\Models\TroubleTicket;
use App\Models\ProblemManageProgress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProblemManage extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function problem_manage_progress()
    {
        return $this->hasMany(ProblemManageProgress::class);
    }

    public function progressResult()
    {
        return $this->problem_manage_progress();
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function troubleTicket()
    {
        return $this->belongsTo(TroubleTicket::class, 'nomor_ticket', 'nomor_ticket');
    }

    public function lastProgress()
    {
        return $this->belongsTo(ProblemManageProgress::class, 'last_progress_id', 'id');
    }
}
