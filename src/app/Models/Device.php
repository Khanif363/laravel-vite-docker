<?php

namespace App\Models;

use App\Models\TroubleTicket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function troubleTickets()
    {
        return $this->hasMany(TroubleTicket::class);
    }
}
