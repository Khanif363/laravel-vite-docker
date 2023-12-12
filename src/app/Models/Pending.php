<?php

namespace App\Models;

use App\Models\TroubleTicketProgress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pending extends Model
{
    use HasFactory;

    protected $guarded = [];
    // protected $dates = ['duration'];

    public function troubleTicketProgress()
    {
        return $this->belongsTo(TroubleTicketProgress::class);
    }
}
