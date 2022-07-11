<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register()
    {
        try {
            // validasi request
            $validator = Validator::make(request()->all(), [
                'username' => 'required|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
            ]);

            // jika validasi gagal
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $user = User::create([
                'username' => request('username'),
                'email' => request('email'),
                'password' => Hash::make(request('password'))
            ]);

            // // generate token, auto login, atau hanya respon berhasil
            return response()->json(['message' => 'Successfully created user!']);
        } catch (\Throwable $th) {
            // munculkan error sesuai validasi
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
