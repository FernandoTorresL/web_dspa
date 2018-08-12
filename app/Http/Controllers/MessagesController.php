<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMessageRequest;
use App\Message;
use Illuminate\Http\Request;

class MessagesController extends Controller
{

    public function create(CreateMessageRequest $request)
    {
        $user = $request->user();

        $message = Message::create([
            'user_id' => $user->id,
            'content' => $request->input('message'),
            'image' => 'http://placeimg.com/600/338/tech?'  . mt_rand(0,1000)
//            'image' => 'https://picsum.photos/600/338?random'
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
