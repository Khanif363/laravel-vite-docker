<?php

namespace App\Models;

use App\Models\UserCR;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItChangeRequest extends Model
{
    use HasFactory;

    // protected $connection = 'mysql2';
    // protected $table = 'it_change_request';
    // protected $guarded = [];
    // // public $timestamps = false;
    // const CREATED_AT = 'created';
    // const UPDATED_AT = 'last_update';

    // public static function maxNumCR()
    // {
    //     $max = 0;
    //     $max = self::selectRaw('MAX(CONVERT(RIGHT(no,4), SIGNED INTEGER)) AS max_number')->get()[0]->max_number;

    //     $new_number = (int)$max + 1;
    //     $new_number = sprintf("%04s", $new_number);

    //     return $new_number;
    // }

    public static function lastId()
    {
        $data = self::select('id')->orderBy('id', 'desc')->first();
        return $data;
    }
}
