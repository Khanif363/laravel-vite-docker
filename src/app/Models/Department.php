<?php

namespace App\Models;

use App\Models\User;
use App\Models\Resume;
use App\Models\Service;
use App\Models\TicketInfo;
use App\Models\TroubleTicket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function ticketInfos()
    {
        return $this->hasMany(TicketInfo::class);
    }

    public function troubleTickets()
    {
        return $this->hasMany(TroubleTicket::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function resume()
    {
        return $this->hasOne(Resume::class);
    }
}
