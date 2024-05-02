<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConnectRequest;
use App\Models\Profile;
use App\Services\ConnectService;
use Illuminate\Http\Request;
use App\Models\Connect;

class ConnectController extends Controller
{
    private ConnectService $connectService;

    public function __construct(ConnectService $connectService)
    {
        $this->connectService = $connectService;
    }

    public function follow(ConnectRequest $request)
    {
        $username = Profile::where('username',$request->validated()['username'])->first()->username;
        if (!$username){
            return response()->json(['error' => 'Invalid data'], 400);
        }

        $connect = $this->connectService->follow($username);

        return response()->json($connect);
    }

    public function unfollow(ConnectRequest $request)
    {
        $username = Profile::where('username',$request->validated()['username'])->first()->username;
        if (!$username){
            return response()->json(['error' => 'Invalid data'], 400);
        }

        $this->connectService->unfollow($username);

        return response("deleted",204);
    }
}
