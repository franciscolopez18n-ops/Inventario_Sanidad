<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use App\Constants\FlashType;
use App\Models\User;

class WelcomeController extends Controller
{
    /**
     * Muestra la vista de bienvenida.
     *
     * @return \Illuminate\View\View
     */
    public function welcome()
    {
        return view('welcome.welcome');
    }

    /**
     * Devuelve los datos del usuario basado en la cookie 'USERPASS'.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function firstLogData()
    {
        $userpass = Cookie::get('USERPASS');
        $user = User::where('user_id', $userpass)->first();

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        return response()->json($user);
    }

    /**
     * Cambia la contraseña del usuario en su primer inicio de sesión.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePasswordFirstLog(Request $request)
    {
        $request->validate([
            'newPassword' => [
                'required',
                'min:6',
                'regex:/[!@#$%^&*(),.?":{}|<>]/'
            ],
            'confirmPassword' => 'required|same:newPassword',
        ], [
            'newPassword.required' => 'La nueva contraseña es obligatoria.',
            'newPassword.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'newPassword.regex' => 'La contraseña debe contener al menos un carácter especial.',
            'confirmPassword.required' => 'La confirmación es obligatoria.',
            'confirmPassword.same' => 'Las contraseñas no coinciden.',
        ]);

        $userId = Cookie::get('USERPASS');
        if (!$userId) {
            return redirect()->back()->withErrors(['user' => 'Cookie de usuario no encontrada']);
        }

        $user = User::where('user_id', $userId)->first();
        if (!$user) {
            return redirect()->back()->withErrors(['user' => 'Usuario no encontrado']);
        }

        // Actualizar contraseña y marcar primer inicio de sesión como completado
        $user->hashed_password = Hash::make($request->newPassword);
        $user->first_log = 1;
        $user->save();

        return redirect()->route('welcome')->with(FlashType::SUCCESS, 'Contraseña actualizada con éxito.');
    }
}
