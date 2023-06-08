<?php

namespace App\Http\Controllers\API\Auth;


use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    /**
     * handle create new user
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = $request->all();
            $user['password'] = Hash::make($user['password']);
            $user = User::create($user);
            $auth['token'] =  $user->createToken('ProductManagement')->plainTextToken;
            $auth['user'] =  $user;

            return $this->sendResponse($auth, 'User register successfully.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * handle user login
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                $user = Auth::user();
                $auth['token'] =  $user->createToken('ProductManagement')->plainTextToken;
                $auth['user'] =  $user;

                return $this->sendResponse(['auth' => $auth], 'User logged in successfully.');
            }
            else
                return $this->sendError('Unauthorised.');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            Auth::user()->currentAccessToken()->delete();
            return $this->sendResponse([], 'User logged out successfully.');
        }catch (\Exception $e){
            return $this->sendError($e->getMessage());
        }

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getAuthState(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $auth['token'] =  $user->createToken('tersea')->plainTextToken;
            $auth['user'] =  $user;
            return $this->sendResponse(['auth' => $auth], 'User info loaded successfully.');
        }catch (\Exception $e){
            return $this->sendError($e->getMessage());
        }

    }
}
