<?php

namespace App\Http\Controllers;
namespace App\Http\Helpers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class Helpers extends Controller

{
    public static function formatdate($pdate)
    {
        if (isset($pdate))
            return Carbon::parse($pdate)->formatLocalized('%d%b%Y');
        else
            return null;
    }

    public static function formatdate2($pdate)
    {
        if (isset($pdate))
            return Carbon::parse($pdate)->formatLocalized('%d de %B, %Y');
        else
            return null;
    }

    public static function formatdatetime2($pdatetime)
    {
        if (isset($pdatetime))
            return Carbon:: parse($pdatetime)->formatLocalized('%d de %B, %Y %H:%Mh');
        //Reemplaza a: \Carbon\Carbon::parse($solicitud->created_at)->'%d-%b-%Y %H:%Mh'
        else
            return null;
    }

    public static function format_datetime_short($pdatetime)
    {
        if (isset($pdatetime))
            return Carbon:: parse($pdatetime)->formatLocalized('%d%b%y %H:%Mh');
        else
            return null;
        //Reemplaza a: \Carbon\Carbon::parse($solicitud->created_at)->'%d-%b-%Y %H:%Mh'
    }

    public static function format_datetime_short2($pdatetime)
    {
        if (isset($pdatetime))
            return Carbon:: parse($pdatetime)->formatLocalized('%d-%b-%Y,%H:%Mh');
        else
            return null;
    }

    public static function format_datetime_short3($pdatetime)
    {
        if (isset($pdatetime))
            return Carbon:: parse($pdatetime)->formatLocalized('%d-%b-%Y %H:%Mh');
        else
            return null;
    }

    public static function format_datetime_short4($pdatetime)
    {
        if (isset($pdatetime))
            return Carbon:: parse($pdatetime)->formatLocalized('%d%b%y %H:%M:%S');
        else
            return null;
        //Reemplaza a: \Carbon\Carbon::parse($solicitud->created_at)->'%d-%b-%Y %H:%Mh'
    }

    public static function format_datetime_compact($pdatetime)
    {
        if (isset($pdatetime))
            return Carbon:: parse($pdatetime)->formatLocalized('%d%b%Y %H:%M');
        else
            return null;
    }

    public static function formatdif_dias2($pdatetime1, $pdatetime2)
    {
        return date_diff( $pdatetime1, $pdatetime2 )->format('%d día(s)');
    }

    public static function set_status_sol_flow($status_sol_id)
    {
        switch($status_sol_id) {
            case 1:     $color = 'light';       $color_text = 'dark';       $possibles_status = [ 2, 3, 4, 5 ]; break;
            case 2:     $color = 'warning';     $color_text = 'warning';    $possibles_status = [ 1 ]; break;
            case 3:     $color = 'danger';      $color_text = 'danger';     $possibles_status = [ 1, 2 ]; break;
            case 4:     $color = 'secondary';   $color_text = 'secondary';  $possibles_status = [ 3, 5 ]; break;
            case 5:     $color = 'primary';     $color_text = 'primary';    $possibles_status = [ ]; break;
            case 6:     $color = 'info';        $color_text = 'dark';       $possibles_status = [ 7, 8, 9 ]; break;
            case 7:     $color = 'danger';      $color_text = 'danger';     $possibles_status = [ 0 ]; break;
            case 8:     $color = 'success';     $color_text = 'success';    $possibles_status = [ 0 ]; break;
            case 9:     $color = 'secondary';   $color_text = 'secondary';  $possibles_status = [ 3, 7, 8 ]; break;
            default:    $color = 'light';       $color_text = 'dark';       $possibles_status = [ ];
        }

        return [
            'color_solicitud'       => $color,
            'color_text_solicitud'  => $color_text,
            'possibles_status_sol'  => $possibles_status
            ];
    }
}
