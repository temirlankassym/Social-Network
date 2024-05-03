<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Connect;
use App\Models\Interaction;
use App\Services\InteractionService;
use Illuminate\Http\Request;
use App\Models\Post;

class InteractionController extends Controller
{
    private InteractionService $interactionService;

    public function __construct(InteractionService $interactionService)
    {
        $this->interactionService = $interactionService;
    }

    /**
     * @OA\Post(
     *     path="/api/like/{post}",
     *     summary="Like post",
     *     tags={"Like"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="int", example="1", description="ID of post"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Successful follow"),
     *     @OA\Response(response="400", description="Bad request"),
     *     @OA\Response(response="401", description="Unauthorized")
     * )
     */

    public function like(Post $post)
    {
        if(Interaction::where('post_id',$post->id)
                ->where('type','like')
                ->where('username',auth()->user()->username)
                ->count() > 0) {
            return response("Already liked",400);
        }

        $this->interactionService->like($post);

        return response("Success",200);
    }

    /**
     * @OA\Post(
     *     path="/api/unlike/{post}",
     *     summary="Remove like",
     *     tags={"Unlike"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="int", example="1", description="ID of post")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Successful follow"),
     *     @OA\Response(response="400", description="Bad request"),
     *     @OA\Response(response="401", description="Unauthorized")
     * )
     */

    public function unlike(Post $post)
    {
        if($post->likes <= 0){
            return response("No likes to remove",400);
        }

        $this->interactionService->unlike($post);

        return response()->json($post);
    }

    /**
     * @OA\Post(
     *     path="/api/comment",
     *     summary="Comment post",
     *     tags={"Add Comment"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"id","description"},
     *             @OA\Property(property="id", type="int", example="1", description="ID of post"),
     *             @OA\Property(property="description", type="string", example="Nice post!"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Successful comment"),
     *     @OA\Response(response="400", description="Bad request"),
     *     @OA\Response(response="401", description="Unauthorized")
     * )
     */

    public function comment(CommentRequest $request)
    {
        $data = $request->validated();

        Interaction::create([
            'post_id' => $data['id'],
            'username' => auth()->user()->username,
            'type' => 'comment',
            'description' => $data['description']
        ]);

        return response("Success",200);
    }

    /**
     * @OA\Post(
     *     path="/api/uncomment",
     *     summary="Delete comment",
     *     tags={"Delete Comment"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(property="id", type="int", example="1", description="ID of comment")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Successful deletion of comment"),
     *     @OA\Response(response="400", description="Bad request"),
     *     @OA\Response(response="401", description="Unauthorized")
     * )
     */

    public function uncomment(Request $request)
    {
        $comment = Interaction::where('id',$request->id)
            ->where('username',auth()->user()->username)
            ->first();

        if(!$comment){
            return response("could not delete someone else's comment",401);
        }

        $comment->delete();

        return response("Success",200);
    }
}
