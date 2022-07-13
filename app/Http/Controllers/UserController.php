<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($username)
    {
        return new UserResource(User::where('username', $username)->first());
    }

    public function getActivity($username)
    {
        return new UserResource(User::where('username', $username)->with('forums', 'forumComments')->first());
    }
}
