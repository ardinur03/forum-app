<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class ForumController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth:api');
        return auth()->shouldUse('api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Forum::with('user:id,username')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $this->getAuthUser();
        $this->validateRequest();

        $slug = Str::slug(request('title'));
        $slugCount = Forum::where('slug', 'like', $slug . '%')->count();
        $slug =  $slugCount ? Str::slug(request('title'), '-') . '-' . time() : $slug;

        $user->forums()->create([
            'title' => request('title'),
            'body' => request('body'),
            'slug' => $slug,
            'category' => request('category'),
        ]);

        return response()->json(['message' => 'Successfully created forum !']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->getAuthUser();
        $forum = $this->checkForum($id);
        return $forum;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = $this->getAuthUser();
        $forum = $this->checkForum($id);

        $this->validateRequest();

        $this->checkOwnership($user->id, $forum->user_id);

        // cek slug jika sudah ada maka generate lagi
        $slug = Str::slug(request('title'));
        $slugCount = Forum::where('slug', 'like', $slug . '%')->count();
        $slug =  $slugCount ? Str::slug(request('title'), '-') . '-' . time() : $slug;

        $forum->update([
            'title' => request('title'),
            'body' => request('body'),
            'slug' => $slug,
            'category' => request('category'),
        ]);

        return response()->json(['message' => 'Successfully updated forum !']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $forum = $this->checkForum($id);
        $user = $this->getAuthUser();
        $this->checkOwnership($user->id, $forum->user_id);
        $forum->delete();
        return response()->json(['message' => 'Successfully deleted forum !']);
    }

    private function validateRequest()
    {
        $validator = Validator::make(request()->all(), [
            'title' => 'required|min:5',
            'body' => 'required|min:10',
            'category' => 'required',
        ]);

        if ($validator->fails()) {
            response()->json($validator->errors())->send();
            exit;
        }
    }

    private function getAuthUser()
    {
        try {
            return auth()->userOrFail();
        } catch (UserNotDefinedException $e) {
            response()->json(['message' => "Not authenticated, you have to login first !"])->send();
            exit;
        }
    }

    private function checkOwnership($authUser, $ownerUser)
    {
        if ($authUser != $ownerUser) {
            response()->json(['message' => 'You are not authorized to update this forum !'])->send();
            exit;
        }
    }

    private function checkForum($idParam)
    {
        try {
            return Forum::with('user:id,username')->findOrFail($idParam);
        } catch (\Throwable $th) {
            response()->json(['message' => 'Forum not found !'])->send();
            exit;
        }
    }
}
