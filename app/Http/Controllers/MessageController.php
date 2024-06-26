<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Http\Resources\UserResource;
use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json(UserResource::collection($users));
    }
    public function show($username)
    {
        $sent = Message::where('sender',auth()->user()->username)
            ->where('receiver',$username)->get();
        $received = Message::where('receiver',auth()->user()->username)
            ->where('sender',$username)->get();
        $messages = $sent->concat($received);

        $user = User::where('username',$username)->first();

        return response()->json([
            'username' => $user->username,
            'messages' => MessageResource::collection($messages)
        ]);
    }
}
