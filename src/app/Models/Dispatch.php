<?php

namespace App\Models;

use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dispatch extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function department_from()
    {
        return $this->belongsTo(Department::class, 'department_from_id', 'id');
    }

    public function department_to()
    {
        return $this->belongsTo(Department::class, 'department_to_id', 'id');
    }

    public function troubleTicketProgress()
    {
        return $this->belongsTo(TroubleTicketProgress::class, 'trouble_ticket_progress_id' . 'id');
    }
}
