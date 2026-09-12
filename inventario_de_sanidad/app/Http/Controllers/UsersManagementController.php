<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Constants\AlertType;
use App\Models\User;
use Carbon\Carbon;
use App\Mail\PasswordChanged;
use App\Mail\UserCreation;
use Illuminate\Support\Facades\Mail;

class UsersManagementController extends Controller {
    /**
     * Muestra la vista para crear un nuevo usuario.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        return view('users.create');
    }


    /**
     * Muestra la vista principal de gestión de usuarios.
     *
     * @return \Illuminate\View\View
     */
    public function manageIndex() {
        return view('users.manage.index',);
    }

    /**
     * Devuelve todos los usuarios en formato JSON ordenados por fecha de creación descendente.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dataUsers() {
        return response()->json(User::orderBy('created_at','desc')->get());
    } 

    /**
     * Crea un nuevo usuario validando los datos y generando una contraseña aleatoria.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        // Generar contraseña aleatoria de 8 caracteres
        $password = self::generateRandomPassword(8);


        // Validar los datos del formulario
        $credentials = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'user_type' => 'required'
        ], [
            'nombre.required' => 'Debe introducir el nombre.',
            'apellidos.required' => 'Debe introducir los apellidos.',
            'email.required' => 'Debe introducir el email.',
            'email.email' => 'Debe introducir un email válido.',
            'email.unique' => 'Ese email ya está registrado.',
            'user_type.required' => 'Debe seleccionar un tipo de usuario.'
        ]);

        // Crear el usuario
        User::create([
            'first_name'       => $credentials["nombre"],
            'last_name'        => $credentials["apellidos"],
            'email'            => $credentials["email"],
            'hashed_password'  => Hash::make($password),
            'user_type'        => $credentials["user_type"],
            'first_log'        => false,
            'created_at'       => Carbon::now('Europe/Madrid'),
        ]);

        Mail::to($credentials["email"])->send(new UserCreation($password,$credentials["nombre"],$credentials["apellidos"],$credentials["email"]));

        return back()->withPush(AlertType::SUCCESS, 'Usuario ' . $credentials["nombre"] . ' ' . $credentials["apellidos"] . ' creado con éxito.');
    }

    private static function generateRandomPassword($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+[]{}|;:,.<>?';
        $password = '';
        for ($i = 0, $max = strlen($characters) - 1; $i < $length; $i++) {
            $password .= $characters[random_int(0, $max)];
        }

        return $password;
    }
    
    public function destroy(User $user) {
        $user->delete();

        return back()->withPush(AlertType::SUCCESS, 'Usuario dado de baja con éxito.');
    }

    public function changePassword(User $user) {
        $password = self::generateRandomPassword(8);

        $user->hashed_password = Hash::make($password);
        $user->first_log = 0;
        $user->save();

        Mail::to($user->email)->send(new PasswordChanged($password, $user->first_name, $user->last_name));

        return back()->withPush(AlertType::SUCCESS, 'Contraseña cambiada con éxito y enviada por correo al usuario.');
    }
}
