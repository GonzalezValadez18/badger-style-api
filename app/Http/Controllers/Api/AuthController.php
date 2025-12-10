<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Lang;


class AuthController extends Controller
{
    /**
     * Restablece la contraseña del usuario.
     */
    public function resetPassword(Request $request)
    {
        // 1. Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 2. Intentar restablecer la contraseña
        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();

                // Disparar evento de reseteo de contraseña
                event(new PasswordReset($user));
            }
        );

        // 3. Devolver la respuesta según el resultado
        if ($status == Password::PASSWORD_RESET) {
            return response()->json(['message' => Lang::get($status)], 200);
        }

        // Si el token es inválido o ha expirado
        return response()->json([
            'message' => Lang::get($status)
        ], 400);
    }

    /**
     * Maneja la solicitud de registro de un nuevo usuario.
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        // 1. La validación se maneja en ForgotPasswordRequest, por lo que podemos buscar al usuario de forma segura.
        $user = User::where('email', $request->email)->first();

        // 2. Intentar enviar el correo para restablecer la contraseña.
        $status = Password::sendResetLink($request->only('email'));

        // 3. Devolver la respuesta según el resultado.
        switch ($status) {
            case Password::RESET_LINK_SENT:
                return response()->json([
                    'message' => 'Hemos enviado por correo electrónico el enlace para restablecer la contraseña.',
                    'username' => $user->username,
                    // ADVERTENCIA DE SEGURIDAD:
                    // Devolver la contraseña, incluso hasheada, es un riesgo de seguridad muy alto.
                    // NO se recomienda descomentar la siguiente línea en producción.
                    // 'password_hash' => $user->password,
                ], 200);
            case Password::INVALID_USER:
                return response()->json(['message' => 'No existe un usuario con esa dirección de correo electrónico.'], 404);
            default:
                return response()->json([
                    'message' => 'Se encontró el usuario, pero no se pudo enviar el correo de restablecimiento. Por favor, verifica la configuración de tu servidor de correo (SMTP).',
                    'username' => $user->username
                ], 500);
        }
    }
    
    public function register(Request $request)
    {
        // 1. Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 2. Crear el nuevo usuario
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. (Opcional) Crear un token para auto-login
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. Devolver una respuesta de éxito (201 Created)
        return response()->json([
            'message' => 'Usuario registrado exitosamente.',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    /**
     * Maneja la solicitud de inicio de sesión del usuario.
     */
    public function login(Request $request)
    {
        // 1. Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422); // 422 Unprocessable Entity
        }

        // 2. Intentar autenticar al usuario por nombre de usuario
        $credentials = $request->only('username', 'password');

        if (!Auth::attempt($credentials)) {
            // Si la autenticación falla, devuelve un error 401 Unauthorized
            return response()->json([
                'message' => 'Las credenciales proporcionadas son incorrectas.'
            ], 401);
        }

        // 3. Si la autenticación es exitosa, obtén el usuario y crea un token
        $user = User::where('username', $request->username)->firstOrFail();

        // Revoca todos los tokens anteriores para forzar un único inicio de sesión
        $user->tokens()->delete();

        // Crea un nuevo token para el usuario
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => '¡Hola ' . $user->name . ', bienvenido!',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    /**
     * Método de prueba para verificar la configuración de correo.
     */
    public function testMail()
    {
        try {
            // Intenta enviar un correo de prueba simple
            \Illuminate\Support\Facades\Mail::raw('Este es un correo de prueba para verificar la conexión SMTP.', function ($message) {
                $message->to('jleonardo18071999@gmail.com')
                        ->subject('Prueba de Correo desde Laravel');
            });

            return response()->json(['message' => '¡Correo de prueba enviado! Revisa tu bandeja de entrada en Mailtrap.'], 200);

        } catch (\Exception $e) {
            // Si falla, devuelve el mensaje de error exacto.
            return response()->json(['message' => 'Error al enviar el correo.', 'error' => $e->getMessage()], 500);
        }
    }
}
