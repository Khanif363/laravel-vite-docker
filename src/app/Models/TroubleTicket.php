<?php

namespace App\Models;

use App\Models\User;
use App\Models\Device;
use App\Models\Resume;
use App\Models\Catalog;
use App\Models\Service;
use App\Models\Location;
use App\Models\DeleteLog;
use App\Models\Attachment;
use App\Models\Department;
use App\Models\TicketInfo;
use App\Models\ResumeEmail;
use App\Models\ChangeManage;
use App\Models\Notification;
use App\Models\ProblemManage;
use App\Models\MantoolsDatacom;
use App\Models\TroubleEngineer;
use App\Models\TroubleTicketProgress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class TroubleTicket extends Model
{
    use HasFactory;

    protected $guarded = [];

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';


    protected $dates = ['event_datetime', 'created_date', 'last_updated_date', 'closed_date', 'technical_closed_date'];


    public function troubleTicketProgress()
    {
        return $this->hasMany(TroubleTicketProgress::class);
    }

    public function ttpDispatch()
    {
        return $this->troubleTicketProgress();
    }

    public function ttpEngineerAssignment()
    {
        return $this->troubleTicketProgress();
    }
    public function ttpTechnicalClose()
    {
        return $this->troubleTicketProgress();
    }

    public function lastProgress()
    {
        return $this->belongsTo(TroubleTicketProgress::class, 'last_progress_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }

    public function troubleEngineers()
    {
        return $this->belongsToMany(TroubleEngineer::class, 'trouble_engineers');
    }

    public function lastRecord()
    {
        return $this->latest()->first();
    }

    public function ticketInfo()
    {
        return $this->hasOne(TicketInfo::class);
    }

    public function catalog()
    {
        return $this->hasOne(Catalog::class);
    }

    public function catalogs()
    {
        return $this->hasMany(Catalog::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function eventLocation() {
        return $this->belongsTo(Location::class, 'event_location_id');
    }

    public function resumeEmail() {
        return $this->hasMany(ResumeEmail::class, 'trouble_ticket_id');
    }

    public function changes() {
        return $this->hasOne(ChangeManage::class, 'ticket_reference_id');
    }

    public function rca() {
        return $this->hasOne(ProblemManage::class, 'nomor_ticket');
    }

    public function mantoolsDatacom() {
        return $this->hasOne(MantoolsDatacom::class, 'id', 'mantools_datacom_id');
    }

    public function notifications(): MorphMany
    {
        return $this->MorphMany(Notification::class, 'notificationable');
    }

    public function deleteLogs(): MorphOne
    {
        return $this->morphOne(DeleteLog::class, 'deletable');
    }
}
