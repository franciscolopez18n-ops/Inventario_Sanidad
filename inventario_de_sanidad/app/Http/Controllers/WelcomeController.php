<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Constants\AlertType;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller {
    /**
     * Muestra la vista de bienvenida.
     *
     * @return \Illuminate\View\View
     */
    public function index() {
        return view('welcome.index');
    }

    /**
     * Cambia la contraseña del usuario en su primer inicio de sesión.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePasswordFirstLog(Request $request) {
        $request->validate([
            'new_password' => [
                'required',
                'min:6',
                'regex:/[!@#$%^&*(),.?":{}|<>]/'
            ],
            'confirm_password' => 'required|same:new_password',
        ], [
            'new_password.required' => 'La nueva contraseña es obligatoria.',
            'new_password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'new_password.regex' => 'La contraseña debe contener al menos un carácter especial.',
            'confirm_password.required' => 'La confirmación es obligatoria.',
            'confirm_password.same' => 'Las contraseñas no coinciden.',
        ]);

        $user = User::find(Auth::id());

        // Actualizar contraseña y marcar primer inicio de sesión como completado
        $user->hashed_password = Hash::make($request->new_password);
        $user->first_log = 1;
        $user->save();

        return redirect()->route('welcome')->withPush(AlertType::SUCCESS, 'Contraseña actualizada con éxito.');
    }
}
