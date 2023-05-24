<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\ResetPasswordJob;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\ForgetPasswordRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LoginController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credencials = $request->only('password', 'email');

            if (!Auth::attempt($credencials)) {
                return response()->json(['message' => 'Credenciales incorrectas.']);
            }
            $user = Auth::user();

            $token =  $user->createToken("auth_token")->plainTextToken;

            return response()->json([
                'message' => 'successfully',
                "access_token" => $token
            ], 200);
        } catch (\Exception $e) {
            $this->response->catch($e->getMessage());
        }
    }

    public function logout(): JsonResponse
    {
        try {
            auth()->user()->tokens()->delete();
            return response()->json([
                'message' => 'logout',
            ]);
        } catch (\Exception $e) {
            $this->response->catch($e->getMessage());
        }
    }

    /**
     * cambia la contraseña del usuario
     *
     * @param ForgetPasswordRequest $request
     * @return JsonResponse
     * 
     * @throws ModelNotFoundException si el usuario no es encontrado por su email
     * @throws Exception si ocurre un error inesperado
     */

    public function forgetPassword(ForgetPasswordRequest $request): JsonResponse
    {
        try {
            $user = User::where("email", $request->email)->first();

            $user->remember_token = Str::random(15);

            $user->save();

            ResetPasswordJob::dispatch($user);

            return response()->json(['message' => 'revisa tu email para poder cambiar tu contraseña']);
        } catch (ModelNotFoundException $e) {
            return $this->response->ModelError($e->getMessage(), "email");
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }

    /**
     * Se recibe el token correcto para poder cambiar el token
     *
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     * 
     * @throws ModelNotFoundException si el usuario no es encontrado por su token
     * @throws Exception si ocurre un error inesperado
     */
    public function resetPassword(ResetPasswordRequest $request,): JsonResponse
    {
        try {
            $user = User::where("remember_token", $request->remember_token)->first();

            if (!$user) {
                return response()->json(['message' => 'Token incorrecto, vuelve a intentarlo']);
            }

            $user->remember_token = "";

            $user->password = bcrypt($request->password);

            $user->save();

            return $this->response->success('cambiado la contraseña');
        } catch (ModelNotFoundException $e) {
            return $this->response->ModelError($e->getMessage(), "token");
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage(), 500);
        }
    }
}
