<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function create(Request $request)
    {
        $this->validate($request, [
            'message' => ['required', 'max:160']
        ], [
            'message.required' => 'Por favor escribe tu mensaje.',
            'message.max' => 'El mensaje no puede superar los 160 caracteres.'
        ]);

        return 'Llego!';
    }

    public function show(Message $message)
    {
        return view('messages.show', [
            'message' => $message
        ]);
    }
}
