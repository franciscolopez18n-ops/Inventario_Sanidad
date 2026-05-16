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
| Autenticación
|--------------------------------------------------------------------------
*/
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/', [LoginController::class, 'login'])->name('login.process');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Rutas protegidas
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Bienvenida / Primer Acceso
    Route::get('/welcome', [WelcomeController::class, 'welcome'])->name('welcome');
    Route::post('/welcome', [WelcomeController::class, 'changePasswordFirstLog'])->name('changePasswordFirstLog');
    Route::get('/firstLogData', [WelcomeController::class, 'firstLogData']);

    /*
    |--------------------------------------------------------------------------
    | Administrador
    |--------------------------------------------------------------------------
    */
    Route::middleware('check.role:admin')->group(function () {

        // Usuarios
        Route::prefix('users')->group(function () {
            Route::get('/create', [UsersManagementController::class, 'showCreateUser'])->name('users.createUser');
            Route::post('/create', [UsersManagementController::class, 'altaUsers'])->name('altaUsers.process');
            Route::get('/management', [UsersManagementController::class, 'showUsersManagement'])->name('users.management');
            Route::post('/management/delete', [UsersManagementController::class, 'bajaUsers'])->name('bajaUsers.process');
            Route::post('/management/password', [UsersManagementController::class, 'changePasswordUser'])->name('password.process');
            Route::get('/usersManagementData', [UsersManagementController::class, 'usersManagementData']);
        });

        // Materiales
        Route::prefix('materials')->group(function () {
            Route::get('/index', [MaterialManagementController::class, 'index'])->name('materials.index');
            Route::get('/materialsData', [MaterialManagementController::class, 'materialsData']);
            Route::get('/create', [MaterialManagementController::class, 'createForm'])->name('materials.create');
            Route::post('/store', [MaterialManagementController::class, 'storeBatch'])->name('materials.store');
            Route::post('/upload-temp', [MaterialManagementController::class, 'uploadTemp'])->name('materials.uploadTemp');
            Route::get('{material}/edit', [MaterialManagementController::class, 'edit'])->name('materials.edit');
            Route::post('{material}/update', [MaterialManagementController::class, 'update'])->name('materials.update');
            Route::post('{material}/destroy', [MaterialManagementController::class, 'destroy'])->name('materials.destroy');

            // Nueva sección en desarrollo
            Route::get('/update', [MaterialManagementController::class, 'updateIndex'])->name('materials.update.index');
            Route::get('/update/{material}', [MaterialManagementController::class, 'updateManualEdit'])->name('materials.update.manual');
            Route::get('/update/{material}/storage/{storage}', [MaterialManagementController::class, 'updateQrEdit'])->name('materials.update.qr');
        });

        // Almacenamiento
        Route::prefix('storages')->group(function () {
            Route::get('/update/{material}/{currentLocation}/edit', [StorageController::class, 'editView'])->name('storages.edit');
            Route::post('/update/{material}/{currentLocation}/process', [StorageController::class, 'updateBatch'])->name('storages.updateBatch');
            Route::post('/destroy/{material}/{currentLocation}', [StorageController::class, 'destroy'])->name('storages.destroy');
        });

        // Historial
        Route::prefix('historical')->group(function () {
            Route::get('/reserve', [HistoricalManagementController::class, 'reserve'])->name('historical.reserve');
            Route::get('/historialModificaciones', [HistoricalManagementController::class, 'showModificationsHistorical'])->name('historical.modificationsHistorical');
            Route::get('/modificationsHistoricalData', [HistoricalManagementController::class, 'modificationsHistoricalData']);
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Profesor
    |--------------------------------------------------------------------------
    */
    Route::middleware('check.role:teacher')->group(function () {

        // Almacenamiento docente
        Route::prefix('storages')->group(function () {
            Route::get('/update/{material}/{currentLocation}/teacher/edit', [StorageController::class, 'teacherEditView'])->name('storages.teacher.edit');
            Route::post('/update/{material}/{currentLocation}/teacher/process', [StorageController::class, 'subtractToUse'])->name('storages.subtract.teacher');
        });

        // Actividades
        Route::prefix('activities')->group(function () {
            Route::get('/activityTeacherData', [ActivityController::class, 'activityTeacherData']);
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Estudiante
    |--------------------------------------------------------------------------
    */
    Route::middleware('check.role:student')->group(function () {

        // Actividades
        Route::prefix('activities')->group(function () {
            Route::get('/create', [ActivityController::class, 'createForm'])->name('activities.create');
            Route::post('/store', [ActivityController::class, 'store'])->name('activities.store');
            Route::get('/activityData', [ActivityController::class, 'activityData']);
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Compartidas
    |--------------------------------------------------------------------------
    */

    // Actividades compartidas
    Route::middleware('check.role:student,teacher')->group(function () {
        Route::get('/activities/history', [ActivityController::class, 'historyView'])->name('activities.history');
    });

    // Gestión de almacenamiento compartido
    Route::middleware('check.role:admin,teacher')->group(function () {
        Route::prefix('storages')->group(function () {
            Route::get('/update', [StorageController::class, 'updateView'])->name('storages.updateView');
            Route::get('/updateData', [StorageController::class, 'updateData'])->name('storages.updateData');
        });
    });

    // Historial compartido
    Route::prefix('historical')->group(function () {
        Route::get('/use', [HistoricalManagementController::class, 'use'])->name('historical.use');
        Route::get('/historicalData', [HistoricalManagementController::class, 'historicalData']);
    });
});