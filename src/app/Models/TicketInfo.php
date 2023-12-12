<?php

namespace App\Models;

use App\Models\Department;
use App\Models\TroubleTicket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketInfo extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $guarded = [];

    public function troubleTicket()
    {
        return $this->belongsTo(TroubleTicket::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
