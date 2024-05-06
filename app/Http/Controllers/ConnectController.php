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

    /**
     * @OA\Post(
     *     path="/api/follow",
     *     summary="Follow a user",
     *     tags={"Follow"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"username"},
     *             @OA\Property(property="username", type="string", example="madikensky"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Successful follow"),
     *     @OA\Response(response="400", description="Bad request"),
     *     @OA\Response(response="401", description="Unauthorized")
     * )
     */

    public function follow(ConnectRequest $request)
    {
        $username = Profile::where('username',$request->validated()['username'])->first()->username;
        if (!$username){
            return response()->json(['error' => 'Invalid data'], 400);
        }

        $this->connectService->follow($username);

        return response()->json("Success");
    }

    /**
     * @OA\Post(
     *     path="/api/unfollow",
     *     summary="Unfollow a user",
     *     tags={"Unfollow"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"username"},
     *             @OA\Property(property="username", type="string", example="madikensky"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Successful unfollowing"),
     *     @OA\Response(response="400", description="Bad request"),
     *     @OA\Response(response="401", description="Unauthorized")
     * )
     */

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
