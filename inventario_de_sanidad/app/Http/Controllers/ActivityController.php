<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Constants\FlashType;
use App\Models\Activity;
use App\Models\MaterialActivity;
use App\Models\Material;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    /**
     * Muestra el formulario de creación de actividades con la lista de materiales disponibles.
     *
     * @return \Illuminate\View\View
     */
    public function createForm()
    {
        $teachers = User::where('user_type', 'teacher')->get();

        return view('activities.create')->with('materials', Material::all())->with('teachers',$teachers);
    }

    /**
     * Devuelve todas las actividades de un alumno en formato JSON ordenados por fecha de creación descendente.
     * @return mixed|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function activityData()
    {
        $user = User::find(Cookie::get('USERPASS'));
        if (!$user) {
            return back()->with(FlashType::ERROR, 'Usuario no encontrado.');
        }
        
        $activities = $user->activities()
            ->with('materials','teacher')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($activities);
    } 

    /**
     * Devuelve todas las actividades asignadas a un profesor en formato JSON ordenados por fecha de creación descendente.
     * @return mixed|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function activityTeacherData()
    {
        $user = User::find(Cookie::get('USERPASS'));
        if (!$user) {
            return back()->with(FlashType::ERROR, 'Usuario no encontrado.');
        }
        
        $activities = Activity::with('materials','teacher','user')
                    ->where('teacher_id',$user->user_id)
                    ->orderBy('created_at', 'desc')
                    ->get();
        return response()->json($activities);
    }

    /**
     * Muestra el historial de actividades del usuario autenticado.
     * @return mixed|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function historyView()
    {
        $user = User::find(Cookie::get('USERPASS'));
        if (!$user) {
            return back()->with(FlashType::ERROR, 'Usuario no encontrado.');
        }
        
        $activities = $user->activities()
            ->with('materials')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('activities.history')->with('activities', $activities);
    }

    /**
     * Almacena una nueva actividad y sus materiales asociados en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request  Petición con los datos del formulario.
     * @return \Illuminate\Http\RedirectResponse   Redirección con mensaje de éxito o error.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'  => 'required',
            'activity_datetime'=> 'required|date',
            'teacher_id'=> 'required',
            'materialsBasketInput' => 'required'
        ], [
            'title.required'  => 'Debe introducir la descripción de la actividad.',
            'activity_datetime.required' => 'Debe introducir la fecha y hora de la actividad.',
            'teacher_id.required' =>'Debe introducir el nombre del profesor',
            'materialsBasketInput.required' => 'Debe introducir datos a la cesta'
        ]);

        // Convierte la cadena JSON de la cesta a array asociativo.
        $basket = json_decode($validated['materialsBasketInput'], true) ?? [];

        // Si no hay datos válidos en la cesta, redirige con mensaje de error.
        if (empty($basket) || !is_array($basket)) {
            return back()->with(FlashType::ERROR, 'No hay materiales en la cesta.');
        }

        // Recupera el ID del usuario desde la cookie.
        $user_id = Cookie::get('USERPASS');

        // Si no hay usuario válido, redirige con error.
        if (!$user_id || !User::find($user_id)) {
            return back()->with(FlashType::ERROR, 'Usuario no válido.');
        }

        try {
            // Inicia una transacción de base de datos para garantizar consistencia.
            DB::transaction(function () use ($basket, $validated, $user_id) {
                // Crea una nueva instancia de actividad.
                $activity = new Activity();
                $activity->user_id = $user_id;
                $activity->title = $validated['title'];
                $activity->teacher_id = $validated['teacher_id'];
                $activity->created_at = $validated['activity_datetime'];
                $activity->save();

                // Llama a función auxiliar para asociar los materiales a la actividad
                $this->storeMaterialsActivity($activity, $basket);
            });

            // Limpia la cookie de la cesta después de completar la operación.
            Cookie::queue(Cookie::forget('materialsBasket'));

            // Redirige con mensaje de éxito.
            return back()->with(FlashType::SUCCESS, 'Actividad insertada correctamente.');
        } catch (\Exception $e) {
            // Si algo falla, redirige con el mensaje de error.
            return back()->with(FlashType::ERROR, 'Error al insertar la actividad: ' . $e->getMessage());
        }
    }

    /**
     * Almacena la relación entre una actividad y los materiales utilizados.
     * 
     * @param \App\Models\Activity $activity    Instancia de la actividad recién creada.
     * @param mixed $basket                     Lista de materiales con sus unidades.
     * @return void
     */
    private function storeMaterialsActivity(Activity $activity, $basket)
    {
        foreach ($basket as $data) {
            MaterialActivity::create([
                'activity_id' => $activity->activity_id,
                'material_id' => $data['material_id'],
                'units'       => $data['units']
            ]);
        }
    }
}
