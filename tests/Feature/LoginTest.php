<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private $login_uri;
    private $forget_password_uri;
    private $reset_password_uri;

    public function setUp(): void
    {
        parent::setUp();
        $this->login_uri = 'api/login';
        $this->forget_password_uri = 'api/forget-password';
        $this->reset_password_uri = 'api/reset-password';
    }

    public function test_login()
    {

        $this->withoutExceptionHandling();

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('Password.123'),
        ]);

        $response = $this->post('/api/login', [
            'email' => 'test@example.com',
            'password' => 'Password.123',
        ])->assertStatus(200);

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_credenciales_incorrectas()
    {

        $this->withoutExceptionHandling();

        $user = User::factory()->create([
            'password' => 'Password.123',
        ]);

        $response = $this->post($this->login_uri, [
            'email' => $user->email,
            'password' => 'Password.mala1'
        ]);

        $response->assertJson(['message' => 'Credenciales incorrectas.']);
    }

    public function test_logout_deberia_invalidar_el_token()
    {
        $this->withoutExceptionHandling();

        // Simula la autenticación de un usuario
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        // Hace la solicitud para el logout
        $response = $this->post('/api/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        // Verifica que el token haya sido invalidado
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => get_class($user),
        ]);

        // Verifica que la respuesta sea correcta
        $response->assertJson([
            'message' => 'logout',
        ]);
    }

    public function test_forget_password()
    {
        $this->withoutExceptionHandling();

        User::factory()->create([
            'email' => 'test@example.com'
        ]);

        // Simula el envío de una solicitud de cambio de contraseña
        $response = $this->post($this->forget_password_uri, ['email' => 'test@example.com']);

        // Verifica que la respuesta sea correcta
        $response->assertJson([
            'message' => 'revisa tu email para poder cambiar tu contraseña',
        ])->assertStatus(200);
    }

    public function test_reset_password()
    {
        $this->withoutExceptionHandling();

        // Crea un usuario y genera un token de recordatorio
        $user = User::factory()->create([
            'remember_token' => Str::random(15),
        ]);

        // Simula el envío de una solicitud de cambio de contraseña con el token válido
        $response = $this->post($this->reset_password_uri, [
            'remember_token' => $user->remember_token,
            'password' => 'Password.1953',
        ]);

        // Verifica que la contraseña se haya cambiado correctamente
        $this->assertTrue(Hash::check('Password.1953', $user->fresh()->password));

        // Verifica que la respuesta sea correcta
        $response->assertJson([
            'msg' => 'Se ha cambiado la contraseña satisfactoriamente.',
        ])->assertStatus(200);
    }

    public function test_reset_password_token_incorrecto()
    {
        $this->withoutExceptionHandling();

        // Crea un usuario y genera un token de recordatorio
        User::factory()->create([
            'remember_token' => Str::random(15),
        ]);

        // Simula el envío de una solicitud de cambio de contraseña con el token válido
        $response = $this->post($this->reset_password_uri, [
            'remember_token' => '123456789123456',
            'password' => 'Password.1953',
        ]);

        // Verifica que la respuesta sea correcta
        $response->assertJson([
            'message' => 'Token incorrecto, vuelve a intentarlo'
        ]);
    }
}
