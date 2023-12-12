<?php

namespace App\Models;

use App\Models\User;
use App\Models\TroubleTicketProgress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attachment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function troubleTicketProgress()
    {
        return $this->belongsTo(TroubleTicketProgress::class, 'trouble_ticket_progress_id', 'id');
    }
}
