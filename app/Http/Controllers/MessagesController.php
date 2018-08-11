<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMessageRequest;
use App\Message;
use Illuminate\Http\Request;

class MessagesController extends Controller
{

    public function create(CreateMessageRequest $request)
    {
        $message = Message::create([
            'content' => $request->input('message'),
            'image' => 'https://picsum.photos/600/338?random'
        ]);

        return redirect('/messages/'.$message->id);
    }

    public function show(Message $message)
    {

        return view('messages.show', [
            'message' => $message
        ]);
    }
}
