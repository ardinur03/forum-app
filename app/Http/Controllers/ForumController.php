<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class ForumController extends Controller
{

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
    public function store()
    {
        try {
            $validator = Validator::make(request()->all(), [
                'title' => 'required|min:5',
                'body' => 'required|min:10',
                'category' => 'required',
            ]);
            
            if($validator->fails()){
                return response()->json($validator->errors());
            }
            
            $user = auth()->userOrFail();

            // cek slug jika sudah ada maka generate lagi
            $slug = Str::slug(request('title'));
            $slugCount = Forum::where('slug', 'like', $slug . '%')->count();
            $slug =  $slugCount ? Str::slug(request('title'), '-') . '-' . time() : $slug ;

            $user->forums()->create([
                'title' => request('title'),
                'body' => request('body'),
                'slug' => $slug,
                'category' => request('category'),
            ]);

            return response()->json(['message' => 'Successfully created forum !']);
        } catch (UserNotDefinedException $e) {
            return response()->json(['message' => "Not authenticated, you have to login first !"]);
        }

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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
