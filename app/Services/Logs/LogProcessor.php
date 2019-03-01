<?php
/**
 * Created by PhpStorm.
 * Date: 01/03/2019
 * Time: 11:33 AM
 */

namespace App\Services\Logs;

class LogProcessor
{
    public function __invoke(array $record)
    {
        $record['extra'] = [
            'user_id' => auth()->user() ? auth()->user()->id : NULL,
            'origin' => request()->headers->get('origin'),
            'ip' => request()->server('REMOTE_ADDR'),
            'user_agent' => request()->server('HTTP_USER_AGENT')
        ];

        /*
        $record['extra'] = [
            'type_aud_id' => 1, //INFO
            'action_aud_id' => 1, //Visitar HOME
            'operation_aud_id' => 5, //Sin afectar registros
            'table_aud_id' => NULL,
            'table_pk' => NULL,
            'ip_aud_id' => 1, // --
            'user_id' => auth()->user() ? auth()->user()->id : NULL,
            'information' => 'IP:' . request()->server('REMOTE_ADDR') . ' ' . 'ORIGIN:' . request()->headers->get('origin')
        ];
        */

        //dd($record);
        return $record;
    }
}
