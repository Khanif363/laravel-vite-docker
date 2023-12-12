<?php

namespace App\Models;

use App\Models\UserMutation;
use App\Models\UserPosition;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User3Easy extends Model
{

    protected $connection = 'mysql3';
    protected $table = 'm_karyawan';

    protected $guarded = [];

    use HasFactory;

    public function jabatan() {
        return $this->hasOneThrough(
            UserPosition::class,
            UserMutation::class,
            'm_karyawan_id',
            'id',
            'id',
            'r_jabatan_id'
        )
        ->join('m_karyawan', 'h_mutasi.m_karyawan_id', '=', 'm_karyawan.id')
        ->where('m_karyawan.is_aktif', 1)
        ->where('h_mutasi.is_aktif', 1);
    }
}
