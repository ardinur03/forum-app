<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

trait AuthUserTrait
{
    private function getAuthUser()
    {
        try {
            return auth()->userOrFail();
        } catch (UserNotDefinedException $e) {
            response()->json(['message' => "Not authenticated, you have to login first !"])->send();
            exit;
        }
    }

    private function checkOwnership($ownerUser)
    {
        $user = $this->getAuthUser();

        if ($user->id != $ownerUser) {
            response()->json(['message' => 'You are not authorized to update this forum !'])->send();
            exit;
        }
    }
}
