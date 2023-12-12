<?php

namespace App\Models;

use App\Models\User;
use App\Models\Pending;
use App\Models\Diagnosa;
use App\Models\Dispatch;
use App\Models\Engineer;
use App\Models\Attachment;
use App\Models\Department;
use App\Models\TroubleTicket;
use App\Models\TechnicalClose;
use App\Models\EngineerAssignment;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TroubleTicketProgress extends Model
{
    use HasFactory;
    use HasRelationships;

    protected $guarded = [];

    const CREATED_AT = 'inputed_date';
    const UPDATED_AT = 'updated_date';
    // public $timestamps = false;

    protected $dates = ['inputed_date', 'updated_date'];

    public function troubleTicket()
    {
        return $this->belongsTo(TroubleTicket::class, 'trouble_ticket_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'inputer_id');
    }

    public function diagnose()
    {
        return $this->hasOne(Diagnose::class, 'trouble_ticket_progress_id');
    }

    public function pending()
    {
        return $this->hasOne(Pending::class);
    }

    public function engineerAssignment()
    {
        return $this->hasOne(EngineerAssignment::class);
    }

    public function engineerUser()
    {
        return $this->hasManyDeep(
            User::class,
            [EngineerAssignment::class, Engineer::class],
            [
                'trouble_ticket_progress_id',
                'engineer_assignment_id',
                'id'
            ],
            [
                'id',
                'id',
                'user_id'
            ]
        );
    }

    public function departmentDispatch()
    {
        return $this->hasOneThrough(
            Department::class,
            Dispatch::class,
            'trouble_ticket_progress_id',
            'id',
            'id',
            'department_to_id'
        );
    }

    public function technicalClose()
    {
        return $this->hasOne(TechnicalClose::class);
    }

    public function dispatch()
    {
        return $this->hasOne(Dispatch::class, 'trouble_ticket_progress_id', 'id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'trouble_ticket_progress_id', 'id');
    }

    public function editor() {
        return $this->hasOne(User::class, 'id', 'editor_id');
    }
}
