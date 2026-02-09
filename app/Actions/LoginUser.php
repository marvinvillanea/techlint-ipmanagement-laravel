<?php
namespace App\Actions;

use App\Repositories\RefreshTokenRepository;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\ValidationException;

class LoginUser
{
    public function execute(array $credentials): array
    {
        // Dito natin i-th-throw ang error para mahuli ng catch sa Controller
        if (!$token = JWTAuth::attempt($credentials)) {
            throw new \Exception('Invalid email or password');
        }

        return [
            'user' => auth()->user(),
            'token' => $token,
            'refresh_token' =>  RefreshTokenRepository::create(auth()->user()['id'])
        ];
    }
}