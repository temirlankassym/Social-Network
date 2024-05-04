<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function postMessage(Request $request, $username)
    {
        $messageContent = $request->input('message');

        Message::create([
            'sender' => auth()->user()->username,
            'receiver' => $username,
            'text' => $messageContent
        ]);

        MessageSent::dispatch(auth()->user()->username, $username, $messageContent);

        return response()->json(['status' => 'Message sent successfully.']);
    }
}
