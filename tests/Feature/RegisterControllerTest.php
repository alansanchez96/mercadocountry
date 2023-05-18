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

    public function test_un_usuario_se_puede_registrar()
    {
        // Preparamos los Datos de un usuario
        $data = [
            'name' => 'Usuario',
            'lastname' => 'Publico',
            'email' => 'user@user.com',
            'password' => 'ContraSegura1',
            'password_confirmation' => 'ContraSegura1'
        ];

        // Validamos que el usuario no se encuentre registrado previamente
        $this->assertDatabaseMissing('users', ['email' => $data['email']]);

        // Nos aseguramos que el usuario envie una peticion POST a nuestra API
        $response = $this->post(route('auth.register'), $data);

        // Registramos al usuario si pasa la validación
        $this->assertDatabaseHas('users', ['email' => $data['email']]);

        // Que la respuesta sea correcta
        $response->assertExactJson([
            // 'message' => 'Gracias por registrarte en MercadoCountry. Te hemos enviado las instrucciones para confirmar tu cuenta a tu casilla de email.'
            'message' => 'Todo ok'
        ]);

        $response->assertStatus(201);
    }

    public function test_el_campo_name_es_obligatorio()
    {
        // Hacemos que 'name' esté vacío
        $data = [
            'lastname' => 'Publico',
            'email' => 'user@user.com',
            'password' => 'ContraSegura1',
            'password_confirmation' => 'ContraSegura1'
        ];

        $response = $this->post(route('auth.register'), $data);

        // Obtenemos los errores del validador de la sesión
        $errors = $response->baseResponse->getSession()->get('errors');

        // Verificamos si hay un error en el campo 'name'
        $this->assertTrue($errors->has('name'));

        // Verificamos que el mensaje de error en el campo 'name' sea el esperado
        $this->assertEquals(['The name field is required.'], $errors->get('name'));
    }

    public function test_el_campo_name_debe_ser_string()
    {
        // Hacemos que 'name' sea un entero
        $data = [
            'name' => (int) 123456,
            'lastname' => 'Publico',
            'email' => 'user@user.com',
            'password' => 'ContraSegura1',
            'password_confirmation' => 'ContraSegura1'
        ];

        $response = $this->post(route('auth.register'), $data);

        // Obtenemos los errores del validador de la sesión
        $errors = $response->baseResponse->getSession()->get('errors');

        // Verificamos si hay un error en el campo 'name'
        $this->assertTrue($errors->has('name'));

        // Verificamos que el mensaje de error en el campo 'name' sea el esperado
        $this->assertEquals(['The name field must be a string.'], $errors->get('name'));
    }

    public function test_el_campo_name_no_puede_superar_los_60_caracteres()
    {
        // Hacemos que 'name' supere los 60 caracteres
        $data = [
            'name' => 'Este es un nombre extremadamente largo que contiene más de 60 caracteres y se utiliza para probar la validación del nombre en Laravel.',
            'lastname' => 'Publico',
            'email' => 'user@user.com',
            'password' => 'ContraSegura1',
            'password_confirmation' => 'ContraSegura1'
        ];

        // Realizamos la peticion POST
        $response = $this->post(route('auth.register'), $data);

        // Obtenemos los errores del validador de la sesión
        $errors = $response->baseResponse->getSession()->get('errors');

        // Verificamos si hay un error en el campo 'name'
        $this->assertTrue($errors->has('name'));

        // Verificamos que el mensaje de error en el campo 'name' sea el esperado
        $this->assertEquals(['The name field must not be greater than 60 characters.'], $errors->get('name'));
    }

    public function test_el_campo_lastname_es_obligatorio()
    {
        // Hacemos que 'lastname' esté vacío
        $data = [
            'name' => 'Usuario',
            'email' => 'user@user.com',
            'password' => 'ContraSegura1',
            'password_confirmation' => 'ContraSegura1'
        ];

        $response = $this->post(route('auth.register'), $data);

        // Obtenemos los errores del validador de la sesión
        $errors = $response->baseResponse->getSession()->get('errors');

        // Verificamos si hay un error en el campo 'lastname'
        $this->assertTrue($errors->has('lastname'));

        // Verificamos que el mensaje de error en el campo 'lastname' sea el esperado
        $this->assertEquals(['The lastname field is required.'], $errors->get('lastname'));
    }

    public function test_el_campo_lastname_debe_ser_string()
    {
        // Hacemos que 'lastname' sea un entero
        $data = [
            'name' => 'Usuario',
            'lastname' => (int) 123456,
            'email' => 'user@user.com',
            'password' => 'ContraSegura1',
            'password_confirmation' => 'ContraSegura1'
        ];

        $response = $this->post(route('auth.register'), $data);

        // Obtenemos los errores del validador de la sesión
        $errors = $response->baseResponse->getSession()->get('errors');

        // Verificamos si hay un error en el campo 'lastname'
        $this->assertTrue($errors->has('lastname'));

        // Verificamos que el mensaje de error en el campo 'lastname' sea el esperado
        $this->assertEquals(['The lastname field must be a string.'], $errors->get('lastname'));
    }

    public function test_el_campo_lastname_no_puede_superar_los_60_caracteres()
    {
        // Hacemos que 'lastname' supere los 60 caracteres
        $data = [
            'name' => 'Usuario',
            'lastname' => 'Este es un apellido extremadamente largo que contiene más de 60 caracteres y se utiliza para probar la validación del nombre en Laravel.',
            'email' => 'user@user.com',
            'password' => 'ContraSegura1',
            'password_confirmation' => 'ContraSegura1'
        ];

        // Realizamos la peticion POST
        $response = $this->post(route('auth.register'), $data);

        // Obtenemos los errores del validador de la sesión
        $errors = $response->baseResponse->getSession()->get('errors');

        // Verificamos si hay un error en el campo 'lastname'
        $this->assertTrue($errors->has('lastname'));

        // Verificamos que el mensaje de error en el campo 'lastname' sea el esperado
        $this->assertEquals(['The lastname field must not be greater than 60 characters.'], $errors->get('lastname'));
    }

    public function test_el_campo_email_es_obligatorio()
    {
        // Hacemos que 'email' este vacío
        $data = [
            'name' => 'Usuario',
            'lastname' => 'Publico',
            'email' => '',
            'password' => 'ContraSegura1',
            'password_confirmation' => 'ContraSegura1'
        ];

        // Realizamos la peticion POST
        $response = $this->post(route('auth.register'), $data);

        // Obtenemos los errores del validador de la sesión
        $errors = $response->baseResponse->getSession()->get('errors');

        // Verificamos si hay un error en el campo 'email'
        $this->assertTrue($errors->has('email'));

        // Verificamos que el mensaje de error en el campo 'email' sea el esperado
        $this->assertEquals(['The email field is required.'], $errors->get('email'));
    }

    public function test_el_campo_email_debe_ser_valido()
    {
        $data = [
            'name' => 'Usuario',
            'lastname' => 'Publico',
            'email' => 'EmailInvalido', // Hacemos que 'email' sea invalido
            'password' => 'ContraSegura1',
            'password_confirmation' => 'ContraSegura1'
        ];

        // Realizamos la peticion POST
        $response = $this->post(route('auth.register'), $data);

        // Obtenemos los errores del validador de la sesión
        $errors = $response->baseResponse->getSession()->get('errors');

        // Verificamos si hay un error en el campo 'email'
        $this->assertTrue($errors->has('email'));

        // Verificamos que el mensaje de error en el campo 'email' sea el esperado
        $this->assertEquals(['The email field must be a valid email address.'], $errors->get('email'));
    }

    public function test_el_campo_email_no_puede_superar_los_255_caracteres()
    {
        // Hacemos que 'email' supere los 255 caracteres
        $data = [
            'name' => 'Usuario',
            'lastname' => 'Publico',
            'email' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890_abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890_abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890_abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890_@example.com',
            'password' => 'ContraSegura1',
            'password_confirmation' => 'ContraSegura1'
        ];

        // Realizamos la peticion POST
        $response = $this->post(route('auth.register'), $data);

        // Obtenemos los errores del validador de la sesión
        $errors = $response->baseResponse->getSession()->get('errors');

        // Verificamos si hay un error en el campo 'email'
        $this->assertTrue($errors->has('email'));

        // Verificamos que el mensaje de error en el campo 'email' sea el esperado
        $this->assertEquals(['The email field must not be greater than 255 characters.'], $errors->get('email'));
    }
    
    public function test_el_email_no_debe_estar_registrado()
    {
        // Usuario Registrado
        $user = User::factory()->create(); 

        $data = [
            'name' => 'Usuario',
            'lastname' => 'Publico',
            'email' => $user->email, // Intentamos registrar el email de un usuario ya registrado
            'password' => 'ContraSegura1',
            'password_confirmation' => 'ContraSegura1'
        ];
        
        // Realizamos la peticion POST
        $response = $this->post(route('auth.register'), $data);
        
        // Obtenemos los errores del validador de la sesión
        $errors = $response->baseResponse->getSession()->get('errors');
        
        // Verificamos si hay un error en el campo 'email'
        $this->assertTrue($errors->has('email'));
        
        // Verificamos que el mensaje de error en el campo 'email' sea el esperado
        $this->assertEquals(['The email has already been taken.'], $errors->get('email'));
    }

    public function test_el_campo_password_es_obligatorio()
    {
        $data = [
            'name' => 'Usuario',
            'lastname' => 'Publico',
            'email' => 'user@user.com',
            'password' => '', // Hacemos que el password esté vacio
            'password_confirmation' => 'ContraSegura1'
        ];

        // Realizamos la peticion POST
        $response = $this->post(route('auth.register'), $data);

        // Obtenemos los errores del validador de la sesión
        $errors = $response->baseResponse->getSession()->get('errors');

        // Verificamos si hay un error en el campo 'password'
        $this->assertTrue($errors->has('password'));

        // Verificamos que el mensaje de error en el campo 'password' sea el esperado
        $this->assertEquals(['The password field is required.'], $errors->get('password'));
    }

    public function test_el_campo_password_debe_ser_string()
    {
        $data = [
            'name' => 'Usuario',
            'lastname' => 'Publico',
            'email' => 'user@user.com',
            'password' => (int) 1231231232, // Hacemos que el password sea un integer
            'password_confirmation' => (int) 1231231232
        ];

        // Realizamos la peticion POST
        $response = $this->post(route('auth.register'), $data);

        // Obtenemos los errores del validador de la sesión
        $errors = $response->baseResponse->getSession()->get('errors');

        // Verificamos si hay un error en el campo 'password'
        $this->assertTrue($errors->has('password'));

        // Verificamos que el mensaje de error en el campo 'password' sea el esperado
        $this->assertEquals(['The password field must be a string.'], $errors->get('password'));
    }

    public function test_el_campo_password_debe_tener_minimo_8_caracteres()
    {
        $data = [
            'name' => 'Usuario',
            'lastname' => 'Publico',
            'email' => 'user@user.com',
            'password' => 'Contra1', // Insertamos un password de 7 caracteres
            'password_confirmation' => 'Contra1'
        ];

        // Realizamos la peticion POST
        $response = $this->post(route('auth.register'), $data);

        // Obtenemos los errores del validador de la sesión
        $errors = $response->baseResponse->getSession()->get('errors');

        // Verificamos si hay un error en el campo 'password'
        $this->assertTrue($errors->has('password'));

        // Verificamos que el mensaje de error en el campo 'password' sea el esperado
        $this->assertEquals(['The password field must be at least 8 characters.'], $errors->get('password'));
    }

    public function test_el_campo_password_debe_tener_maximo_16_caracteres()
    {
        $data = [
            'name' => 'Usuario',
            'lastname' => 'Publico',
            'email' => 'user@user.com',
            'password' => 'ContraseñaMasLargaQueLaAnterior1', // Insertamos un password de 17 caracteres
            'password_confirmation' => 'ContraseñaMasLargaQueLaAnterior1'
        ];

        // Realizamos la peticion POST
        $response = $this->post(route('auth.register'), $data);

        // Obtenemos los errores del validador de la sesión
        $errors = $response->baseResponse->getSession()->get('errors');

        // Verificamos si hay un error en el campo 'password'
        $this->assertTrue($errors->has('password'));

        // Verificamos que el mensaje de error en el campo 'password' sea el esperado
        $this->assertEquals(['The password field must not be greater than 16 characters.'], $errors->get('password'));
    }

    public function test_el_campo_password_confirm_debe_ser_igual_a_password()
    {
        $data = [
            'name' => 'Usuario',
            'lastname' => 'Publico',
            'email' => 'user@user.com',
            'password' => 'Contraseña123',
            'password_confirmation' => 'ContraseñaDiferente' // Insertamos un password diferente
        ];

        // Realizamos la peticion POST
        $response = $this->post(route('auth.register'), $data);

        // Obtenemos los errores del validador de la sesión
        $errors = $response->baseResponse->getSession()->get('errors');

        // Verificamos si hay un error en el campo 'password'
        $this->assertTrue($errors->has('password'));

        // Verificamos que el mensaje de error en el campo 'password' sea el esperado
        $this->assertEquals(['The password field confirmation does not match.'], $errors->get('password'));
    }

    public function test_el_campo_password_debe_ser_alpha_numerico()
    {
        $data = [
            'name' => 'Usuario',
            'lastname' => 'Publico',
            'email' => 'user@user.com',
            'password' => 'PasswordTest',
            'password_confirmation' => 'PasswordTest' // Insertamos un password diferente
        ];

        // Realizamos la peticion POST
        $response = $this->post(route('auth.register'), $data);

        // Obtenemos los errores del validador de la sesión
        $errors = $response->baseResponse->getSession()->get('errors');

        // Verificamos si hay un error en el campo 'password'
        $this->assertTrue($errors->has('password'));

        // Verificamos que el mensaje de error en el campo 'password' sea el esperado
        $this->assertEquals(['The password field must contain at least one number.'], $errors->get('password'));
    }

    public function test_el_campo_password_debe_tener_al_menos_1_mayuscula_y_1_minuscula()
    {
        $data = [
            'name' => 'Usuario',
            'lastname' => 'Publico',
            'email' => 'user@user.com',
            'password' => 'passwordtest1',
            'password_confirmation' => 'passwordtest1' // Insertamos un password diferente
        ];

        // Realizamos la peticion POST
        $response = $this->post(route('auth.register'), $data);

        // Obtenemos los errores del validador de la sesión
        $errors = $response->baseResponse->getSession()->get('errors');

        // Verificamos si hay un error en el campo 'password'
        $this->assertTrue($errors->has('password'));

        // Verificamos que el mensaje de error en el campo 'password' sea el esperado
        $this->assertEquals(['The password field must contain at least one uppercase and one lowercase letter.'], $errors->get('password'));
    }
}
