<?php

namespace App\Http\Controllers\User\Concerns;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\UserUpdateEmailJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Authenticatable;

trait HandlesUserProfile
{
    /**
     * Maneja la actualizaciones de información del usuario autenticado 
     *
     * @param Request $request
     * @param Authenticatable $auth
     * @return array
     */
    public function handleProfileUpdate(Request $request, Authenticatable $auth): array
    {
        $messages = [];

        $this->handleNameUpdate($request, $auth);

        if (!empty($request->phone) && $auth->phone !== $request->phone) {
            $this->handlePhoneUpdate($request, $auth);
        }

        $messages[] = 'Tus datos se han guardado';

        if (!empty($request->email) && $auth->email !== $request->email) {
            $messages[] = $this->handleEmailUpdate($request, $auth);
        }

        if (!empty($request->password) && !Hash::check($request->password, $auth->password)) {
            $this->handlePasswordUpdate($request, $auth);
            $messages[] = 'El cambio de contraseña se ha guardado con éxito';
        }

        return ['message' => implode('. ', $messages)];
    }

    /**
     * Actualiza el nombre y apellido
     *
     * @param Request $request
     * @param Authenticatable $auth
     * @return void
     */
    private function handleNameUpdate(Request $request, Authenticatable $auth): void
    {
        $capitalizedName = Str::ucfirst(Str::lower($request->name));
        $capitalizedLastName = Str::ucfirst(Str::lower($request->lastName));

        $data = [];

        if (!empty($request->name)) {
            $data['name'] = $capitalizedName;
        }

        if (!empty($request->lastName)) {
            $data['lastname'] = $capitalizedLastName;
        }

        if (!empty($data)) {
            $auth->update($data);
        }
    }

    /**
     * Actualiza el numero telefonico
     *
     * @param Request $request
     * @param Authenticatable $auth
     * @return void
     */
    private function handlePhoneUpdate(Request $request, Authenticatable $auth): void
    {
        $auth->update([
            'phone' => $request->phone
        ]);
    }

    /**
     * Envia una peticion de registro de Email
     *
     * @param Request $request
     * @param Authenticatable $auth
     * @return string
     */
    private function handleEmailUpdate(Request $request, Authenticatable $auth): string
    {
        $email = Str::lower($request->email);

        DB::table('users_changes')
            ->insert([
                'email_change' => $email,
                'user_id' => $auth->id
            ]);

        $auth->update([
            'code' => Str::upper(Str::random(4))
        ]);

        UserUpdateEmailJob::dispatch($auth, $email);

        return 'Se enviaron las instrucciones en tu nuevo email para confirmar el cambio';
    }

    /**
     * Confirma la peticion del Nuevo Email
     *
     * @param Authenticatable $auth
     * @return void
     */
    public function confirmNewEmail(Authenticatable $auth): void
    {
        $userChange = DB::table('users_changes')
            ->where('user_id', $auth->id)
            ->first();

        $auth->update([
            'email' => Str::lower($userChange->email_change),
            'code' => null,
            'email_verified_at' => Carbon::now()
        ]);

        DB::table('users_changes')
            ->where('user_id', $auth->id)
            ->delete();
    }

    /**
     * Actualiza la contraseña del usuario
     *
     * @param Request $request
     * @param Authenticatable $auth
     * @return void
     */
    private function handlePasswordUpdate(Request $request, Authenticatable $auth): void
    {
        $auth->update([
            'password' => bcrypt($request->password)
        ]);
    }
}
