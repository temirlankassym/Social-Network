<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserCreateRequest;
use App\Models\User;
use App\Models\Profile;
use Couchbase\QueryErrorException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(UserCreateRequest $request)
    {
        $data = $request->validated();

        User::create($data);

        auth()->attempt($data);

        try{
            Profile::create([
                'username' => $data['username'],
                'user_id' => auth()->user()->id
            ]);
        }catch (QueryException $e){
            return ['error' => 'user can only have one account'];
        }

        return response('Success',200);
    }

    public function login(LoginRequest $request)
    {
        if (!$token = auth()->attempt($request->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $token
        ]);
    }
}
