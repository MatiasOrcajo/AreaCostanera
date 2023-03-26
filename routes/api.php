<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('schools', [\App\Http\Controllers\SchoolsController::class, 'listSchools'])->name('list.schools');

Route::get('days', [\App\Http\Controllers\DaysController::class, 'listDays'])->name('list.days');

Route::get('menus', [\App\Http\Controllers\MenuController::class, 'listMenus'])->name('list.menus');

Route::get('formas-de-pago', [\App\Http\Controllers\FormasPagoController::class, 'listFormasPago'])->name('list.formasPago');

Route::get('menus-especiales', [\App\Http\Controllers\MenuController::class, 'listSpecialMenus'])->name('list.menusEspeciales');

Route::get('fiesta-egresados/{id}', [\App\Http\Controllers\GraduatePartyController::class,
    'listGraduatePartyPeople'])->name('list.graduateParty');

Route::get('egresado/{id}', [\App\Http\Controllers\StudentsController::class, 'getStudentFamily'])->name('list.studentFamily');

Route::get('cuota/{cuota}', [\App\Http\Controllers\CuotasController::class, 'getDueStatus']);

Route::put('saldar-cuota/{cuota}', [\App\Http\Controllers\CuotasController::class, 'payDue']);

Route::get('interes', [\App\Http\Controllers\InteresCuotaController::class, 'listInteres'])->name('listInteres');

Route::post('cerrar-precio/{estudiante}', [\App\Http\Controllers\StudentsController::class, 'closePrice']);

Route::get('eventos-terminados', [\App\Http\Controllers\GraduatePartyController::class, 'listFinishedEvents'])->name('list.finishedEvents');
