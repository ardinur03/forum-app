<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuthUserTrait;
use App\Models\Forum;
use App\Models\ForumComment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ForumCommentController extends Controller
{
    use AuthUserTrait;

    public function __construct()
    {
        return auth()->shouldUse('api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $forum_id)
    {
        $user = $this->getAuthUser();
        $this->validateRequest();

        $user->forumComments()->create([
            'body' => request('body'),
            'forum_id' => $forum_id
        ]);

        return response()->json(['message' => 'Successfully comment posted !']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $forumId, $forumid, $commentId)
    {
        $forumComment = ForumComment::find($commentId);
        $this->validateRequest();

        $this->checkOwnership($forumComment->user_id);

        $forumComment->update([
            'body' => request('body'),
        ]);

        return response()->json(['message' => 'Successfully comment updated !']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($forumId, $commentId)
    {
        $forumComment = ForumComment::find($commentId);
        $this->checkOwnership($forumComment->user_id);

        $forumComment->delete();

        return response()->json(['message' => 'Successfully comment deleted !']);
    }

    private function validateRequest()
    {
        $validator = Validator::make(request()->all(), [
            'body' => 'required|min:10',
        ]);

        if ($validator->fails()) {
            response()->json($validator->errors())->send();
            exit;
        }
    }
}
