<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\ProfileResource;
use App\Services\AwsService;
use Illuminate\Http\Request;
use App\Models\Profile;

class ProfileController extends Controller
{
    private AwsService $awsService;

    public function __construct(AwsService $awsService)
    {
        $this->awsService = $awsService;
    }

     /**
     * @OA\Post(
     *     path="/api/profile",
     *     summary="Update profile",
     *     tags={"Update Profile"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="image", type="png,jpeg", example="image.png"),
     *             @OA\Property(property="bio", type="string", example="Hi, it's my new account"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Successful update"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */

    public function update(ProfileUpdateRequest $request)
    {
        $data = $request->validated();

        $file = $request->file('image');

        if($file){
            $data['image'] = $this->awsService->store($file);
        }

        auth()->user()->profile->update($data);

        return response("Success",200);
    }

    public function show(){
        return response()->json(new ProfileResource(auth()->user()->profile));
    }
}
