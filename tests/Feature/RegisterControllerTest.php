<?php

use Tests\TestCase;
use App\Models\User;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    private $route_uri;
    private $validate_email_uri;

    public function setUp(): void
    {
        parent::setUp();
        $this->route_uri = 'api/register';
        $this->validate_email_uri = 'api/validate-email';
    }

    public function test_validar_email()
    {
        $this->withoutExceptionHandling();

        // Nos aseguramos que el usuario envie una peticion POST a nuestra API
        $this->post($this->validate_email_uri, ['email' => 'user@user.com'])
            ->assertStatus(200);

        // Registramos al usuario si pasa la validación
        $this->assertDatabaseCount('users', 1);
    }

    public function test_el_email_no_debe_estar_registrado()
    {
        $this->withoutExceptionHandling();

        // Crear un usuario registrado previamente
        $user = User::factory()->create();

        try {
            $this->post($this->validate_email_uri, ['email' => $user->email]);
        } catch (ValidationException $e) {
            $this->assertEquals('The email has already been taken.', $e->getMessage());
            return;
        }
    }

    public function test_un_usuario_se_puede_registrar()
    {
        $this->withoutExceptionHandling();

        // Preparamos los Datos de un usuario
        $data = [
            'name' => 'Usuario',
            'lastname' => 'Publico',
            'email' => 'user@user.com',
            'password' => 'ContraSegura1.',
        ];

        // Nos aseguramos que el usuario envie una peticion POST a nuestra API
        $this->post($this->validate_email_uri, ['email' => 'user@user.com'])
            ->assertStatus(200);

        $this->post($this->route_uri, $data)
            ->assertStatus(201);

        // Registramos al usuario si pasa la validación
        $this->assertDatabaseCount('users', 1);
    }



    public function test_el_campo_password_debe_tener_minimo_8_caracteres()
    {
        $data = [
            'name' => 'Usuario',
            'lastname' => 'Publico',
            'email' => 'user@user.com',
            'password' => 'Fel.195', // Insertamos un password de 7 caracteres
        ];

        // Realizamos la peticion POST
        $response = $this->post($this->route_uri, $data);

        $response->assertSessionHasErrors(['password' => 'The password field must be at least 8 characters.']);
    }

    public function test_el_campo_password_debe_tener_maximo_16_caracteres()
    {
        $data = [
            'name' => 'Usuario',
            'lastname' => 'Publico',
            'email' => 'user@user.com',
            'password' => 'ContraseñaMasLargaQueLaAnterior1', // Insertamos un password de 17 caracteres
        ];

        // Realizamos la peticion POST
        $response = $this->post($this->route_uri, $data);

        $response->assertSessionHasErrors(['password' =>  "The password field must not be greater than 16 characters."]);
    }

    public function test_el_campo_password_debe_tener_al_menos_1_mayuscula_y_1_minuscula()
    {
        $data = [
            'name' => 'Usuario',
            'lastname' => 'Publico',
            'email' => 'user@user.com',
            'password' => 'passwordte.st1',
        ];

        // Realizamos la peticion POST
        $response = $this->post($this->route_uri, $data);

        $response->assertSessionHasErrors(['password' =>  "The password field must contain at least one uppercase and one lowercase letter."]);
    }

    public function test_el_campo_password_debe_tener_al_menos_un_caracter_especial()
    {
        $data = [
            'name' => 'Usuario',
            'lastname' => 'Publico',
            'email' => 'user@user.com',
            'password' => 'passwordtest1',
        ];

        // Realizamos la peticion POST
        $response = $this->post($this->route_uri, $data);

        // Verificamos que el mensaje de error en el campo 'password' sea el esperado
        $response->assertSessionHasErrors(['password' =>  "The password field must contain at least one symbol."]);
    }
}
