<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Enums\FlashType;
use App\Models\User;
use Carbon\Carbon;
use App\Mail\ChangePassword;
use App\Mail\UserCreation;
use Illuminate\Support\Facades\Mail;

class UsersManagementController extends Controller
{
    /**
     * Muestra la vista para crear un nuevo usuario.
     *
     * @return \Illuminate\View\View
     */
    public function showCreateUser()
    {
        return view('users.createUser');
    }


    /**
     * Muestra la vista principal de gestión de usuarios.
     *
     * @return \Illuminate\View\View
     */
    public function showUsersManagement()
    {
        return view('users.usersManagement',);
    }

    /**
     * Devuelve todos los usuarios en formato JSON ordenados por fecha de creación descendente.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function usersManagementData()
    {
        return response()->json(User::orderBy('created_at','desc')->get());
    } 

    /**
     * Crea un nuevo usuario validando los datos y generando una contraseña aleatoria.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function altaUsers(Request $request)
    {
        // Generar contraseña aleatoria de 8 caracteres
        $password = $this->generateRandomPassword(8);


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

        return back()->with(FlashType::SUCCESS->value, 'Usuario ' . $credentials["nombre"] . ' ' . $credentials["apellidos"] . ' creado con éxito.');
    }

    public function generateRandomPassword($length)
    {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+[]{}|;:,.<>?';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        return $password;
    }
    
    /**
     * Elimina un usuario basado en su ID.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bajaUsers(Request $request)
    {
        $user = $request["user_id"];

        User::where('user_id', $user)->delete();

        return back()->with(FlashType::SUCCESS->value, 'Usuario dado de baja con éxito.');
    }

    public function changePasswordUser(Request $request)
    {
        $user = $request["user_id"];
        $password = $this->generateRandomPassword(8);
        $userInfo = User::where('user_id', $user)->first();

        $userInfo->hashed_password = Hash::make($password);
        $userInfo->first_log = 0;
        $userInfo->save();
        Mail::to($userInfo->email)->send(new ChangePassword($password,$userInfo->first_name,$userInfo->last_name));

        return back()->with(FlashType::SUCCESS->value, 'Contraseña modificada con exito');
    }
}
