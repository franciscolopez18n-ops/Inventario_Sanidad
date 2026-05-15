<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage as StorageFacades;
use App\Models\User;


class LoginController extends Controller
{
    /**
     * Muestra el formulario de login.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesa el login del usuario.
     * Valida datos, verifica credenciales y setea cookies si es correcto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
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

            // Guardar datos del usuario en cookies (duración 1440 minutos = 1 día)
            Cookie::queue('USERPASS', $user->user_id, 1440);
            Cookie::queue('NAME', $user->first_name . " " . $user->last_name, 1440);
            Cookie::queue('EMAIL', $user->email, 1440);
            Cookie::queue('TYPE', $user->user_type, 1440);
            
            return redirect()->route('welcome');
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
    public function logout()
    {
        if (Cookie::get('TYPE') === 'admin') {
            // Borra carpeta temporal y cookies del administrador.
            StorageFacades::disk('public')->deleteDirectory('temp');
            Cookie::queue(Cookie::forget('materialsAddBasket'));
            Cookie::queue(Cookie::forget('materialsBasket'));
        }

        // Borra cookies con los datos del usuario.
        Cookie::queue(Cookie::forget('USERPASS'));
        Cookie::queue(Cookie::forget('NAME'));
        Cookie::queue(Cookie::forget('TYPE'));
        return redirect()->route('login.form');
    }
}
