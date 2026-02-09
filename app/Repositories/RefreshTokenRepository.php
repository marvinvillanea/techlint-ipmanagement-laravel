<?php

namespace App\Repositories;

use App\Models\RefreshToken;
use Illuminate\Support\Str;

class RefreshTokenRepository
{
    public static function create($userId)
    {
        $data = RefreshToken::create([
            'user_id' => $userId,
            'token' => hash('sha256', Str::random(60)),
            'expires_at' => now()->addDays(7)
        ]);

        return $data["token"];
    }

}
