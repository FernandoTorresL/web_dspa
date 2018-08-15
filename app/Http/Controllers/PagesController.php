<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PagesController extends Controller
{
    public function home()
    {
        Log::info('Visitando HOME');
        $messages = Message::paginate(10);

        return view('welcome', [
            'messages' => $messages,
        ]);
    }
}
