<?php

namespace App\Models;

use App\Models\User;
use App\Models\Comment;
use App\Models\Engineer;
use App\Models\Location;
use App\Models\DeleteLog;
use App\Models\Attachment;
use App\Models\TicketInfo;
use App\Models\Notification;
use App\Models\ChangeManageProgress;
use Illuminate\Database\Eloquent\Model;
use App\Models\EngineerAssignmentChanges;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChangeManage extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    use HasFactory;

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    protected $guarded = [];
    protected $casts = [
        'type' => 'json',
    ];

    protected $dates = ['datetime_action', 'created_date', 'last_updated_date', 'closed_date'];

    public function changeManageProgress()
    {
        return $this->hasMany(ChangeManageProgress::class);
    }

    public function lastProgress()
    {
        return $this->belongsTo(ChangeManageProgress::class, 'last_progress_id');
    }

    public function changesInfo()
    {
        return $this->hasOne(TicketInfo::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'change_manage_id');
    }

    public function engineerAssignmentChanges()
    {
        return $this->hasOne(EngineerAssignmentChanges::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    function location() {
        return $this->belongsTo(Location::class);
    }

    public function engineers()
    {
        // return $this->hasManyDeep(Engineer::class, EngineerAssignmentChanges::class, 'change_manage_id', 'engineer_assignment_changes_id', 'id', 'id');
        return $this->hasManyDeep(
            User::class,
            [EngineerAssignmentChanges::class, Engineer::class],
            [
                'change_manage_id',
                'engineer_assignment_changes_id',
                'id'
            ],
            [
                'id',
                'id',
                'user_id'
            ]
        );
    }

    public function engineer() {
        return $this->belongsTo(User::class, 'engineer_id');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->latest();
    }

    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notificationable')->latest();
    }

    public function approval1()
    {
        return $this->belongsTo(User::class, 'approval_level1_id');
    }

    public function approval2()
    {
        return $this->belongsTo(User::class, 'approval_level2_id');
    }

    public function deleteLogs(): MorphOne
    {
        return $this->morphOne(DeleteLog::class, 'deletable');
    }
}
