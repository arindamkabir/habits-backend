<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use ApiResponse;

    public function __invoke(LoginRequest $request)
    {
        $request->authenticate();

        $user = User::query()->firstWhere('email', $request->email);

        $token = $user->createToken('User ' . $user->email)->plainTextToken;

        return $this->ok(
            'Authenticated',
            [
                'token' => $token
            ]
        );
    }
}
