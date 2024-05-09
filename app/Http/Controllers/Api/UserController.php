<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(UserLoginRequest $request): \Illuminate\Http\JsonResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $user->update([
                'ip_address' => $request->getClientIp()
            ]);

            return response()->json([
                "token" => $user->createToken('bearer-token')->plainTextToken,
                "user" => $user,
            ]);
        }

        return response()->json([
            "message" => "Invalid user credentials"
        ], 403);
    }
}
