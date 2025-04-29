<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    public function store(LoginRequest $request): array
    {
        $user = $request->user;
        
        return [
            'user' => $user,
            'token' => $user->createToken('SPA')->plainTextToken,
        ];
    }
}
