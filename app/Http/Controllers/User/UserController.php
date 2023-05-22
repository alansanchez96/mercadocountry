<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Http\Requests\Auth\UserCodeRequest;
use App\Http\Controllers\User\Concerns\HandlesUserProfile;

class UserController extends Controller
{
    use HandlesUserProfile;

    public function getProfile(): UserResource|JsonResponse
    {
        try {
            $user = auth()->user();

            return new UserResource($user);
        } catch (\Exception $e) {
            $this->response->catch($e->getMessage());
        }
    }

    public function update(UserRequest $request): JsonResponse
    {
        try {
            $user = auth()->user();

            $response = $this->handleProfileUpdate($request, $user);

            return response()->json($response);
        } catch (\Exception $e) {
            $this->response->catch($e->getMessage());
        }
    }

    public function confirmEmail(UserCodeRequest $request): JsonResponse
    {
        try {
            $user = User::where('code', $request->validated())->first();

            if (!$user) {
                return response()->json([
                    'error' => 'El codigo no es válido'
                ], 422);
            }

            $this->confirmNewEmail($user);

            return response()->json([
                'message' => 'Has cambiado el email satisfactoriamente'
            ]);
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }
}
