<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Constants\AlertType;
use App\Models\Activity;
use App\Models\MaterialActivity;
use App\Models\Material;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller {
    /**
     * Muestra el formulario de creación de actividades con la lista de materiales disponibles.
     *
     * @return \Illuminate\View\View
     */
    public function createForm() {
        $teachers = User::where('user_type', 'teacher')->get();

        return view('activities.create')->with('materials', Material::all())->with('teachers',$teachers);
    }

    /**
     * Devuelve todas las actividades de un alumno en formato JSON ordenados por fecha de creación descendente.
     * @return mixed|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function activityData() {
        $activities = User::find(Auth::id())->activities()
            ->with('materials', 'teacher')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($activities);
    }

    /**
     * Devuelve todas las actividades asignadas a un profesor en formato JSON ordenados por fecha de creación descendente.
     * @return mixed|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function activityTeacherData() {
        $activities = Activity::with('materials', 'teacher', 'user')
            ->where('teacher_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($activities);
    }

    /**
     * Muestra el historial de actividades del usuario autenticado.
     * @return mixed|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function historyView() {
        $activities = User::find(Auth::id())->activities()
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
    public function store(Request $request) {
        $validated = $request->validate([
            'title'  => 'required',
            'activity_datetime'=> 'required|date',
            'teacher_id'=> 'required',
        ], [
            'title.required'  => 'Debe introducir la descripción de la actividad.',
            'activity_datetime.required' => 'Debe introducir la fecha y hora de la actividad.',
            'teacher_id.required' =>'Debe introducir el nombre del profesor.'
        ]);

        // Convierte la cadena JSON del lote a array asociativo.
        // Los métodos de Laravel esperan cookies encriptadas por ellos mismos, por lo que hay que leerla en crudo
        $batch = json_decode(urldecode($_COOKIE['activityFormBatch'] ?? '[]'), true);

        // Si no hay datos válidos en el lote, redirige con mensaje de error.
        if (empty($batch)) {
            return back()->withInput()->withPush(AlertType::ERROR, 'Debe introducir datos al lote.');
        }

        // Comprobaciones de seguridad por si el frontend fue manipulado.
        foreach ($batch as $material) {
            $validator = validator($material, [
                'id' => 'exists:materials,material_id',
                'name' => 'required|string',
                'units' => 'required|numeric|min:1',
            ]);

            if ($validator->fails()) {
                return back()->withInput()->withPush(AlertType::ERROR, 'Los datos del lote no son válidos.');
            }
        }

        $user_id = Auth::id();

        try {
            // Inicia una transacción de base de datos para garantizar consistencia.
            DB::transaction(function () use ($batch, $validated, $user_id) {
                // Crea una nueva instancia de actividad.
                $activity = new Activity();
                $activity->user_id = $user_id;
                $activity->title = $validated['title'];
                $activity->teacher_id = $validated['teacher_id'];
                $activity->created_at = $validated['activity_datetime'];
                $activity->save();

                // Llama a función auxiliar para asociar los materiales a la actividad
                $this->storeMaterialsActivity($activity, $batch);
            });

            // Limpia la cookie del lote después de completar la operación.
            Cookie::queue(Cookie::forget('activityFormBatch'));

            // Redirige con mensaje de éxito.
            return back()->withPush(AlertType::SUCCESS, 'Actividad registrada correctamente.');
        } catch (\Exception $e) {
            // Si algo falla, redirige con el mensaje de error.
            return back()->withInput()->withPush(AlertType::ERROR, 'Error al insertar la actividad: ' . $e->getMessage());
        }
    }

    /**
     * Almacena la relación entre una actividad y los materiales utilizados.
     * 
     * @param \App\Models\Activity $activity    Instancia de la actividad recién creada.
     * @param mixed $batch                     Lista de materiales con sus unidades.
     * @return void
     */
    private function storeMaterialsActivity(Activity $activity, $batch) {
        foreach ($batch as $data) {
            MaterialActivity::create([
                'activity_id' => $activity->activity_id,
                'material_id' => $data['id'],
                'units'       => $data['units']
            ]);
        }
    }
}
