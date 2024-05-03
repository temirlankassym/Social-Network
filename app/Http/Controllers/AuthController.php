<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserCreateRequest;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(title="Instagram app", version="0.1")
 */

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     tags={"Register"},
     *     @OA\Response(response="200", description="Successfull registration"),
     *     @OA\Response(response="400", description="Bad request"),
     *     @OA\RequestBody(
     *     @OA\JsonContent(
     *      type="object",
     *              @OA\Property(property="username", type="string"),
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="password", type="string"),
     *              @OA\Property(property="password_confirmation", type="string"),
     *      )
     *    )
     * )
     */

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

     /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login user",
     *     tags={"Login"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"password"},
     *             @OA\Property(property="username", type="string", example="JohnDoe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
      *                  @OA\Property(
      *                  property="oneOf",
      *                  type="object",
      *                  required={"username", "email"},
      *                  @OA\Property(property="username", type="string"),
      *                  @OA\Property(property="email", type="string", format="email")
      *              ),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Successful login"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */

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



