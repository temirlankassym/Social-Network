<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\ProfileResource;
use App\Interfaces\PublisherInterface;
use App\Services\AwsService;
use Illuminate\Http\Request;
use App\Models\Profile;

class ProfileController extends Controller
{

    private $publisher;

    private AwsService $awsService;

    public function __construct(AwsService $awsService, PublisherInterface $publisher)
    {
        $this->awsService = $awsService;
        $this->publisher = $publisher;
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

    /**
     * @OA\Schema(
     *      schema="Post",
     *      @OA\Property(property="id", type="integer", example=1),
     *      @OA\Property(property="image", type="string", example="image.png")
     *  )
     *
     * @OA\Get(
     *     path="/api/profile",
     *     summary="Show your profile",
     *     tags={"Show your profile"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="username", type="string", example="username"),
     *             @OA\Property(property="bio", type="string", example="This is a bio"),
     *             @OA\Property(property="image", type="string", example="image.png"),
     *             @OA\Property(property="posts_count", type="integer", example=10),
     *             @OA\Property(property="followers", type="integer", example=5),
     *             @OA\Property(property="following", type="integer", example=3),
     *             @OA\Property(property="posts", type="array", @OA\Items(ref="#/components/schemas/Post"))
     *         )
     *     ),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */

    public function show(){
        return response()->json(new ProfileResource(auth()->user()->profile));
    }

    /**
     *
     * @OA\Get(
     *     path="/api/profile/{username}",
     *     summary="Get someone's profile",
     *     tags={"Get Profile"},
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="username", type="string", example="username"),
     *             @OA\Property(property="bio", type="string", example="This is a bio"),
     *             @OA\Property(property="image", type="string", example="image.png"),
     *             @OA\Property(property="posts_count", type="integer", example=10),
     *             @OA\Property(property="followers", type="integer", example=5),
     *             @OA\Property(property="following", type="integer", example=3),
     *             @OA\Property(property="is_subscribed", type="boolean", example="false"),
     *             @OA\Property(property="posts", type="array", @OA\Items(ref="#/components/schemas/Post"))
     *         )
     *     ),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */

    public function profile($username){
        $profile = Profile::where('username',$username)->first();
        return response()->json(new ProfileResource($profile));
    }

    /**
     * @OA\Post(
     *     path="/api/subscribe",
     *     summary="Subscribe to profile and receive notification when new post will came out",
     *     tags={"Subscribe"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="username", type="string", example="madikensky")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Successful subscription"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */

    public function subscribe(Request $request){
        $this->publisher->addSubscriber($request['username'], auth()->user()->username);
        return response("Success",200);
    }

    /**
     * @OA\Post(
     *     path="/api/unsubscribe",
     *     summary="Unsubscribe from profile",
     *     tags={"Unsubscribe"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="username", type="string", example="madikensky")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Successful unsubscription"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */

    public function unsubscribe(Request $request){
        $this->publisher->removeSubscriber($request['username'], auth()->user()->username);
        return response("Success",200);
    }

    public function subscribers(Request $request){
        return response()->json(Profile::where('username',$request['username'])->first()->subscribers);
    }
}
