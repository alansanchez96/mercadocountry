<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use App\Jobs\SendCodeJob;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\{
    PhoneRequest,
    RegisterRequest,
    UserCodeRequest,
    ValidateNameRequest,
    ValidateEmailRequest,
    ValidatePasswordRequest
};

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
                'email' => Str::lower($request->email),
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

    public function validatePhone(PhoneRequest $request): JsonResponse
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

    /**
     * Validamos que el Codigo enviado a su correo sea válido
     *
     * @param UserCodeRequest $request
     * @return JsonResponse
     */
    public function confirmEmail(UserCodeRequest $request): JsonResponse
    {
        try {
            $user = User::where('code', $request->code)->first();

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

    /**
     * Registramos al usuario por completo
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function registerUser(RegisterRequest $request): JsonResponse
    {
        // Formateo de nombres
        $capitalizedName = Str::ucfirst(Str::lower($request->name));
        $capitalizedLastName = Str::ucfirst(Str::lower($request->lastName));

        try {
            $user = User::where('email', $request->email)->firstOrFail();

            if ($user->email_verified_at === null && $user->code !== null) {
                return response()->json([
                    'error' => 'Debes validar tu correo electrónico para continuar'
                ], 428);
            }

            $user->update([
                'name' => $capitalizedName,
                'lastname' => $capitalizedLastName,
                'phone' => $request->phone,
                'email' => Str::lower($request->email),
                'password' => bcrypt($request->password),
                'code' => null
            ]);

            return response()->json([
                'message' => 'Te has registrado satisfactoriamente.'
            ], 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->response->ModelError($e->getMessage(), 'email');
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }
}
