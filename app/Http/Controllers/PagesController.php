<?php

namespace App\Http\Controllers;

use App\Message;
use App\Track_aud;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PagesController extends Controller
{
    public function home()
    {
        Log::info('WELCOME');

        /*
        $track_audit = Track_aud::create([
            'type_aud_id' => 1, //INFO
            'action_aud_id' => 1, //Visitar HOME
            'operation_aud_id' => 5, //Sin afectar registros
            'table_aud_id' => NULL,
            'table_pk' => NULL,
            'ip_aud_id' => 1, // --
            'user_id' => $user_id, //user_id
            'information' => 'Visitando HOME',
        ]);

        dd($track_audit);
*/
        return view('welcome' );
    }
}
