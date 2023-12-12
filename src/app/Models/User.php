<?php

namespace App\Models;

use App\Models\Role;
use App\Models\User3Easy;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function engineer()
    {
        return $this->hasOne(Engineer::class);
    }

    public function troubleTicketEmail()
    {
        return $this->hasOne(TroubleTicketEmail::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function troubleTicketPendings()
    {
        return $this->hasMany(TroubleTicketPending::class);
    }

    public function troubleTickets()
    {
        return $this->hasMany(TroubleTicket::class);
    }

    public function troubleTicketProgress()
    {
        return $this->hasMany(TroubleTicketProgress::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function accessRights(): BelongsToMany
    {
        return $this->belongsToMany(AccessRight::class, UserAccessRight::class)->withTimestamps();
    }

    public function karyawan() {
        return $this->belongsTo(User3Easy::class, 'id_account', 'id');
    }
}
