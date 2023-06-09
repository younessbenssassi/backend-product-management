<?php

namespace App\Http\Controllers\API\Modules;


use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Api\BaseController as BaseController;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends BaseController
{

    /**
     * @param ProfileRequest $request
     * @return JsonResponse
     */
    public function update(ProfileRequest $request): JsonResponse
    {
        try {
            $user = User::find(Auth::user()->id);

            if(is_null($user))
                return $this->sendError('Profile not found');

            DB::transaction(function ()use (&$user , &$request) {
                if (isset($request->password)) {
                    $user->password = Hash::make($request->password);
                    $user->save();
                }

                $user->update($request->except('hash','password','password_confirmation'));
            });

            return $this->sendResponse([], 'Profile updated successfully.');
        }
        catch (Exception $e){
            return $this->sendError($e->getMessage());
        }
    }

}
