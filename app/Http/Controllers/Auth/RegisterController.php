<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use App\Jobs\SendCodeJob;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UserCodeRequest;
use App\Http\Requests\Auth\ValidateNameRequest;
use App\Http\Requests\Auth\ValidateEmailRequest;
use App\Http\Requests\Auth\ValidatePasswordRequest;

class RegisterController extends Controller
{
   
    /**
     * Validamos el email
     *
     * @param ValidatePasswordRequest $request
     * @return JsonResponse
     */
    public function validateEmail(ValidateEmailRequest $request)
    {
        try {
            // Añadir logica para enviar instrucciones al correo
            $user = User::create([
                'email' => $request->email,
                'code' => Str::upper(Str::random(4))
            ]);

            SendCodeJob::dispatch($user);

            // Respuesta si la validacion pasa
            return response()->json([
                'message' => 'Se ha enviado un codigo a tu casilla de correo electronico.'
            ]);
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }

     /**
     * Validamos los nombres y apellido
     *
     * @param ValidatePasswordRequest $request
     * @return JsonResponse
     */
    public function validateNames(ValidateNameRequest $request): JsonResponse
    {
        return $this->response->statusOk();
    }


    /**
     * Validamos la password
     *
     * @param ValidatePasswordRequest $request
     * @return JsonResponse
     */
    public function validatePassword(ValidatePasswordRequest $request): JsonResponse
    {
        return $this->response->statusOk();
    }

    
    public function confirmEmail(UserCodeRequest $request)
    {
        try {
            $user = User::where('code', $request->validated())->first();

            if (!$user) {
                return response()->json([
                    'message' => 'El codigo no es válido'
                ], 422);
            }

            $user->email_verified_at = Carbon::now();
            $user->code = null;
            $user->save();

            return $this->response->statusOk();
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }

    public function registerUser(RegisterRequest $request): JsonResponse
    {
        try {
            $user = User::where('email', $request->email)->firstOrFail();

            $user->update([
                'name' => $request->name,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'code' => null
            ]);

            return response()->json([
                'message' => 'Te has registrado con satisfactoriamente.'
            ], 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->response->ModelError($e->getMessage(), 'email');
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }
}
