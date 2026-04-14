<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\UsersManagementController;
use App\Http\Controllers\MaterialManagementController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\HistoricalManagementController;
use App\Http\Controllers\ActivityController;

/*
|--------------------------------------------------------------------------
| Autenticación (views/auth)
|--------------------------------------------------------------------------
*/
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/', [LoginController::class, 'login'])->name('login.process');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Bienvenida / Primer Acceso (views/welcome)
|--------------------------------------------------------------------------
*/
Route::get('/firstLogData', [WelcomeController::class, 'firstLogData']);
Route::get('/welcome', [WelcomeController::class, 'welcome'])->name('welcome');
Route::post('/welcome', [WelcomeController::class, 'changePasswordFirstLog'])->name('changePasswordFirstLog');

/*
|--------------------------------------------------------------------------
| Almacenamiento Docente (views/storages/teacher)
|--------------------------------------------------------------------------
*/
Route::middleware('check.teacher.cookie')->group(function () {
    Route::get('/storages/update/{material}/{currentLocation}/teacher/edit', [StorageController::class, 'teacherEditView'])->name('storages.teacher.edit');
    Route::post('/storages/update/{material}/{currentLocation}/teacher/process', [StorageController::class, 'subtractToUse'])->name('storages.subtract.teacher');
});

/*
|--------------------------------------------------------------------------
| Actividades (views/activities)
|--------------------------------------------------------------------------
*/
Route::prefix('activities')->group(function () {
    Route::get('/create', [ActivityController::class, 'createForm'])->name('activities.create');
    Route::get('/history', [ActivityController::class, 'historyView'])->name('activities.history');
    Route::post('/store', [ActivityController::class, 'store'])->name('activities.store');
    Route::get('/activityData', [ActivityController::class, 'activityData']);
    Route::get('/activityTeacherData', [ActivityController::class, 'activityTeacherData']);
});

/*
|--------------------------------------------------------------------------
| Rutas protegidas para Administradores
|--------------------------------------------------------------------------
*/
Route::middleware('check.admin.cookie')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Gestión de Usuarios (views/users)
    |--------------------------------------------------------------------------
    */
    Route::get('/users/create', [UsersManagementController::class, 'showCreateUser'])->name('users.createUser');
    Route::post('/users/create', [UsersManagementController::class, 'altaUsers'])->name('altaUsers.process');
    Route::get('/users/management', [UsersManagementController::class, 'showUsersManagement'])->name('users.management');
    Route::post('/users/management/delete', [UsersManagementController::class, 'bajaUsers'])->name('bajaUsers.process');
    Route::post('/users/management/password', [UsersManagementController::class, 'changePasswordUser'])->name('password.process');
    Route::get('/users/usersManagementData', [UsersManagementController::class, 'usersManagementData']);

    /*
    |--------------------------------------------------------------------------
    | Historial (views/historical)
    |--------------------------------------------------------------------------
    */
    Route::prefix('historical')->group(function () {
        Route::get('/modificationsHistoricalData', [HistoricalManagementController::class, 'modificationsHistoricalData']);
        Route::get('/historicalData', [HistoricalManagementController::class, 'historicalData']);
        Route::get('/historialModificaciones', [HistoricalManagementController::class, 'showModificationsHistorical'])->name('historical.modificationsHistorical');
        Route::get('/{type}', [HistoricalManagementController::class, 'index'])
            ->where('type', 'reserve|use')
            ->name('historical.type');
    });

    /*
    |--------------------------------------------------------------------------
    | Almacenamiento (views/storages)
    |--------------------------------------------------------------------------
    */
    Route::prefix('storages')->group(function () {
        Route::get('/update', [StorageController::class, 'updateView'])->name('storages.updateView');
        Route::get('/updateData', [StorageController::class, 'updateData'])->name('storages.updateData');
        Route::get('update/{material}/{currentLocation}/edit', [StorageController::class, 'editView'])->name('storages.edit');
        Route::post('/update/{material}/{currentLocation}/process', [StorageController::class, 'updateBatch'])->name('storages.updateBatch');
        Route::post('destroy/{material}/{currentLocation}', [StorageController::class, 'destroy'])->name('storages.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Gestión de Materiales (views/materials)
    |--------------------------------------------------------------------------
    */
    Route::prefix('materials')->group(function () {
        Route::get('/index', [MaterialManagementController::class, 'index'])->name('materials.index');
        Route::get('/materialsData', [MaterialManagementController::class, 'materialsData']);
        Route::get('/create', [MaterialManagementController::class, 'createForm'])->name('materials.create');
        Route::post('/store', [MaterialManagementController::class, 'storeBatch'])->name('materials.store');
        Route::post('{material}/destroy', [MaterialManagementController::class, 'destroy'])->name('materials.destroy');
        Route::get('{material}/edit', [MaterialManagementController::class, 'edit'])->name('materials.edit');
        Route::post('{material}/update', [MaterialManagementController::class, 'update'])->name('materials.update');
        Route::post('/upload-temp', [MaterialManagementController::class, 'uploadTemp'])->name('materials.uploadTemp');

        Route::get('/index2', [MaterialManagementController::class, 'index2'])->name('materials.index2');
        Route::get('{material}/edit2', [MaterialManagementController::class, 'edit2'])->name('materials.edit2');
    });
});
