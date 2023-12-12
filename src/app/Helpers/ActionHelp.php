<?php

namespace App\Helpers;

use Illuminate\Support\Facades\View;


class ActionHelp
{
    public static function strReplaceViewList($to_replace)
    {
        $search_str = ['View List ', 'Change Management ', 'Problem Management '];
        $replace_str = ['', '', ''];
        $value_replace = json_decode(str_replace($search_str, $replace_str, ($to_replace ?? null)), true);
        return $value_replace;
    }
}
