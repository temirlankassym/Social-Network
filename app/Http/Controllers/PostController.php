<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Http\Resources\SinglePostResource;
use App\Services\AwsService;
use Illuminate\Http\Request;
use App\Http\Requests\PostCreateRequest;
use App\Models\Post;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    private AwsService $awsService;

    public function __construct(AwsService $awsService)
    {
        $this->awsService = $awsService;
    }

    /**
     * @OA\Post(
     *     path="/api/post",
     *     summary="Create new post",
     *     tags={"Create Post"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"image"},
     *             @OA\Property(property="image", type="png,jpeg", example="image.png"),
     *             @OA\Property(property="description", type="string", example="This is a description")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Successful creation"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */

    public function create(PostCreateRequest $request)
    {
        $data = $request->validated();

        Post::create([
            'profile_id' => auth()->user()->profile->id,
            'image' => $this->awsService->store($request->file('image')),
            'description' => $data['description'] ?? ""
        ]);

        auth()->user()->profile->increment('posts',1);

        return response()->json("Success",200);
    }

    /**
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Get all posts",
     *     tags={"Get Posts"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="400", description="Bad request"),
     *     @OA\Response(response="401", description="Unauthorized"),
     * )
     */

    public function getAllPosts()
    {
        $posts = auth()->user()->profile->post;
        return response()->json([
            'posts' => PostResource::collection($posts)
        ]);
    }

    /**
     *  @OA\Schema(
     *      schema="Comment",
     *      required={"id", "content", "created_at", "updated_at"},
     *      @OA\Property(property="id", type="integer", format="int64", description="The unique identifier of the comment"),
     *      @OA\Property(property="content", type="string", description="The content of the comment"),
     *      @OA\Property(property="created_at", type="string", format="date-time", description="The date and time when the comment was created"),
     *      @OA\Property(property="updated_at", type="string", format="date-time", description="The date and time when the comment was last updated"),
     *  )
     *
     * @OA\Get(
     *     path="/api/post/{post}",
     *     summary="Get information for specific post",
     *     tags={"Get Post"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="image", type="string", example="image.jpg"),
     *             @OA\Property(property="description", type="string", example="This is a description"),
     *             @OA\Property(property="likes", type="integer", example=10),
     *             @OA\Property(property="time", type="string", example="12:00 01 Jan 2022"),
     *             @OA\Property(property="comments", type="array", @OA\Items(ref="#/components/schemas/Comment")),
     *             @OA\Property(property="is_liked", type="boolean", example=true),
     *             @OA\Property(property="users_liked", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(response="400", description="Bad request"),
     *     @OA\Response(response="401", description="Unauthorized"),
     * )
     */

    public function show(Post $post)
    {
        return response()->json([
            'post' => new SinglePostResource($post)
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/post/{post}",
     *     summary="Delete post",
     *     tags={"Delete Post"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="int", example="1", description="Id of post"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Successful creation"),
     *     @OA\Response(response="400", description="Bad request"),
     *     @OA\Response(response="401", description="Unauthorized")
     * )
     */

    public function destroy(Post $post)
    {
        $post->delete();

        if(auth()->user()->profile->posts > 0){
            auth()->user()->profile->decrement('posts',1);
        }

        return response()->json("Success",204);
    }
}
