<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;

class ApiResetPasswordController extends Controller
{
    /**
     * Handle an incoming new password request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Aquí es donde Laravel valida el token y el email.
        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        // Si el estado es PASSWORD_RESET, significa que todo fue bien.
        if ($status == Password::PASSWORD_RESET) {
            return response()->json(['message' => __($status)], 200);
        }

        // Si no, devolvemos un error.
        return response()->json(['message' => __($status)], 400);
    }

    public function showResetForm(Request $request)
    {
        $token = DB::table('password_reset_tokens')->where('token', $request->token)->first();

        if ($token && hash_equals($token->token, $request->token)) {
             return response()->json(['message' => 'Token is valid.'], 200);
        }

        return response()->json(['message' => 'Invalid token.'], 400);
    }
}