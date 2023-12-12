<?php

namespace App\Models;

use App\Models\TroubleTicket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Catalog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('title', 'like', "%{$searchTerm}%");
    }

    public function troubleTicket()
    {
        return $this->belongsTo(TroubleTicket::class);
    }
}
