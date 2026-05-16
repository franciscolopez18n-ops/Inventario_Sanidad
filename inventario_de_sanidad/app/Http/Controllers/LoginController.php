<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage as StorageFacades;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller {
    /**
     * Muestra el formulario de login.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm() {
        if (Auth::check()) {
            return redirect()->route('welcome');
        }

        return view('auth.login');
    }

    /**
     * Procesa el login del usuario.
     * Valida datos, verifica credenciales y setea cookies si es correcto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request) {
        $credentials = $request->validate([
            'user' => 'required',
            'password' => 'required'
        ], [
            'user.required' => 'Debe introducir su número de usuario.',
            'password.required' => 'Debe introducir su contraseña.',
        ]);

        // Buscar usuario por su ID (user_id)
        $user = User::where('email', $credentials['user'])->first();

        // Verificar que usuario exista y que contraseña sea correcta
        if ($user && Hash::check($credentials['password'], $user->hashed_password)) {
            Auth::login($user);
            
            return redirect()->intended(route('welcome'));
        } else {
            return back()->withErrors(['login' => 'Usuario o contraseña incorrectos']);
        }
    }

    /**
     * Realiza logout del usuario.
     * Borra cookies y elimina carpeta temporal.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request) {
        if (Auth::user()->user_type === 'admin') {
            // Borra carpeta temporal y cookies del administrador.
            StorageFacades::disk('public')->deleteDirectory('temp');
            Cookie::queue(Cookie::forget('materialsAddBasket'));
            Cookie::queue(Cookie::forget('materialsBasket'));
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }
}
