<?php

namespace App\Models;

use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AccessRight extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, UserAccessRight::class)->withTimestamps();
    }

    public function department() {
        return $this->belongsTo(Department::class);
    }
}
