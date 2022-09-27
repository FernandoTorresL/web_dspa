<?php

namespace App\Http\Controllers;
namespace App\Http\Helpers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class Helpers extends Controller

{
    public static function formatdate2($pdate)
    {
        return Carbon::parse($pdate)->formatLocalized('%d de %B, %Y');
    }

    public static function formatdatetime2($pdatetime)
    {
        return Carbon:: parse($pdatetime)->formatLocalized('%d de %B, %Y %H:%M');
    }

    public static function formatdif_dias2($pdatetime1, $pdatetime2)
    {
        return date_diff( $pdatetime1, $pdatetime2 )->format('%d día(s)');
    }

}
