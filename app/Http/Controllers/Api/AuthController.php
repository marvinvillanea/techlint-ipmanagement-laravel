<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Helpers\ValidatedReqExecuteReq;
use App\Http\Requests\RegisterUserRequest;
use App\Actions\RegisterUser;
use App\Actions\LoginUser;
use App\DTOs\RegisterUserDTO;
use Illuminate\Support\Str;
use App\Models\RefreshToken;
use App\Models\User;
use App\Repositories\RefreshTokenRepository;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class AuthController extends Controller
{

    public function __construct(
        protected RegisterUser $registerUser,
        protected LoginUser $LoginUser,
    ){}


  
    public function register(RegisterUserRequest $request)
    {
        try {

            $result = ValidatedReqExecuteReq::validatedExecute( RegisterUserDTO::class,  $this->registerUser,   $request);

            return ApiResponse::success($result, 'User registered successfully', 201);
        } catch (\Exception $e) {
           
            return ApiResponse::error($e->getMessage(), null, 500);
        }
    }

    public function login(Request $request) 
    {
        try {
            $credentials = $request->only('email', 'password');
      
            $result = $this->LoginUser->execute($credentials);

            return ApiResponse::success($result, 'Successfully Logged In', 200);
        } catch (\Throwable $e) {
            return ApiResponse::error($e->getMessage(), null, 401);
        }
    }

    public function refresh(Request $request)
    {
       try {


            $refreshToken = RefreshToken::where('token', $request->refresh_token)
                ->where('expires_at', '>', now())
                ->first();

            if (!$refreshToken) {
                  return ApiResponse::error('Invalid refresh token', null, 401);
            }

            $user = User::find($refreshToken->user_id);
            $newAccessToken = JWTAuth::fromUser($user);

            // rotate refresh token
            // $refreshToken->delete();

            $newRefreshToken = RefreshTokenRepository::create($user->id);


            $tok =  $this->respondWithToken($newAccessToken, $newRefreshToken);
            $res = [
                "user" => $user,
            ];

            $response = array_merge($res,$tok);

            return ApiResponse::success($response, 'Successfully', 200);

        } catch(\Exception $e) {
            return ApiResponse::error('Token refresh failed', null, 401);
        }

       
    }

    public function me()
    {

        try {
            return ApiResponse::success(auth()->user(), 'User Information', 200);
        } catch(JWTException $e) {
            return ApiResponse::error('Token refresh failed', null, 401);
        }
        
    }

     // LOGOUT
    public function logout(Request $request)
    {

        RefreshToken::where('token', $request->refresh_token)->delete();
        auth('api')->logout();
        return ApiResponse::success(null, 'Successfully logged out', 200);

    }

    protected function respondWithToken($token,$refresh_token)
    {
        return [
            'token' => $token,
            'refresh_token' => $refresh_token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
     
    }

    
  

}
