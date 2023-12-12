<?php

namespace App\Models;

use App\Models\Department;
use App\Models\TroubleTicket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'category' => 'json',
    ];


    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function troubleTickets()
    {
        return $this->hasMany(TroubleTicket::class);
    }
}
