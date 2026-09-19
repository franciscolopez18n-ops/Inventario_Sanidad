<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\UsersManagementController;
use App\Http\Controllers\MaterialManagementController;
use App\Http\Controllers\TeacherStorageController;
use App\Http\Controllers\MaterialHistoryController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\MaterialQrController;

/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
*/
Route::get('/', [AuthController::class, 'index'])->name('auth.login');
Route::post('/', [AuthController::class, 'verify'])->name('auth.verify');
Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

/*
|--------------------------------------------------------------------------
| Rutas protegidas
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Bienvenida / Primer Acceso
    Route::prefix('welcome')->group(function () {
        Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
        Route::post('/', [WelcomeController::class, 'changePasswordFirstLog'])->name('welcome.change-password');
        Route::get('/data-user', [WelcomeController::class, 'dataUser']);
    });

    /*
    |--------------------------------------------------------------------------
    | Administrador
    |--------------------------------------------------------------------------
    */
    Route::middleware('check.role:admin')->group(function () {

        // Usuarios
        Route::prefix('users')->group(function () {
            // Alta de usuarios
            Route::get('/create', [UsersManagementController::class, 'create'])->name('users.create');
            Route::post('/store', [UsersManagementController::class, 'store'])->name('users.store');
            
            // Gestión de usuarios
            Route::prefix('manage')->group(function () {
                Route::get('/', [UsersManagementController::class, 'manageIndex'])->name('users.manage.index');
                Route::post('/destroy/{user}', [UsersManagementController::class, 'destroy'])->name('users.manage.destroy');
                Route::post('/change-password/{user}', [UsersManagementController::class, 'changePassword'])->name('users.manage.change-password');
            });
        });

        // Materiales
        Route::prefix('materials')->group(function () {
            // Alta de materiales
            Route::get('/create', [MaterialManagementController::class, 'create'])->name('materials.create');
            Route::post('/store', [MaterialManagementController::class, 'store'])->name('materials.store');
            Route::post('/upload-temp', [MaterialManagementController::class, 'uploadTemp'])->name('materials.upload-temp');

            // Gestión de materiales
            Route::prefix('manage')->group(function () {
                Route::get('/', [MaterialManagementController::class, 'manageIndex'])->name('materials.manage.index');
                Route::get('/edit/{material}', [MaterialManagementController::class, 'edit'])->name('materials.manage.edit');
                Route::get('/edit/{material}/storage/{storage}', [MaterialManagementController::class, 'editQr'])->name('materials.manage.edit-qr');
                Route::post('/update/{material}', [MaterialManagementController::class, 'update'])->name('materials.manage.update');
                Route::post('/destroy/{material}', [MaterialManagementController::class, 'destroy'])->name('materials.manage.destroy');
            });

            // Historial
            Route::prefix('history')->group(function () {
                Route::get('/reserve', [MaterialHistoryController::class, 'reserveSummary'])->name('materials.history.reserve');
                Route::get('/modifications', [MaterialHistoryController::class, 'modifications'])->name('materials.history.modifications');
            });

            // Códigos QR
            Route::prefix('qrcodes')->group(function () {
                Route::get('/', [MaterialQrController::class, 'index'])->name('materials.qrcodes.index');
                Route::get('/download-zip', [MaterialQrController::class, 'downloadZip'])->name('materials.qrcodes.download-zip');
                Route::get('/print', [MaterialQrController::class, 'print'])->name('materials.qrcodes.print');
                Route::get('/{file}', [MaterialQrController::class, 'show'])->name('materials.qrcodes.show'); // Códigos QR solo visibles por los administradores
            });
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
            Route::prefix('manage')->group(function () {
                Route::get('/', [TeacherStorageController::class, 'manageIndex'])->name('storages.manage.index');
                Route::get('/data-use-storage', [TeacherStorageController::class, 'dataUseStorage']);
                Route::get('/{material}/{currentLocation}/subtract', [TeacherStorageController::class, 'subtractForm'])->name('storages.manage.subtract.form');
                Route::post('/{material}/{currentLocation}/subtract', [TeacherStorageController::class, 'subtract'])->name('storages.manage.subtract');
            });
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
            Route::get('/create', [ActivityController::class, 'create'])->name('activities.create');
            Route::post('/store', [ActivityController::class, 'store'])->name('activities.store');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Compartidas
    |--------------------------------------------------------------------------
    */

    // Actividades compartidas
    Route::middleware('check.role:student,teacher')->group(function () {
        Route::prefix('activities')->group(function() {
            Route::get('/history', [ActivityController::class, 'history'])->name('activities.history');
        });
    });

    Route::prefix('materials')->group(function() {
        // Sumario de uso compartido
        Route::prefix('history')->group(function () {
            Route::get('/use', [MaterialHistoryController::class, 'useSummary'])->name('materials.history.use');
        });
    });
});