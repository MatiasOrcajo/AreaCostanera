<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\GraduatePartyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthenticatedSessionController::class, 'create']);

Route::group(['middleware' => 'auth'], function(){
    Route::get('/admin/dashboard', [GraduatePartyController::class, 'index'])->name('dashboard');
    Route::get('/admin/evento/{slug}', [GraduatePartyController::class, 'showGraduateParty'])->name('show.graduate');

    Route::get('/admin/escuelas', [\App\Http\Controllers\SchoolsController::class, 'index'])->name('schools');
    Route::post('store/school', [\App\Http\Controllers\SchoolsController::class, 'storeSchool'])->name('store.school');
    Route::get('/admin/escuela/{escuela}', [\App\Http\Controllers\SchoolsController::class, 'showSchool'])->name('show.school');

    Route::get('admin/dias', [\App\Http\Controllers\DaysController::class, 'index'])->name('days');
    Route::post('store/days', [\App\Http\Controllers\DaysController::class, 'storeDays'])->name('store.days');

    Route::get('admin/menus',[\App\Http\Controllers\MenuController::class, 'index'])->name('menus');
    Route::post('store/menu', [\App\Http\Controllers\MenuController::class, 'storeMenu'])->name('store.menu');

    Route::get('admin/formas-de-pago', [\App\Http\Controllers\FormasPagoController::class, 'index'])->name('formasPago');
    Route::post('store/formas-de-pago', [\App\Http\Controllers\FormasPagoController::class, 'storeFormaPago'])->name('store.formaPago');

    Route::post('store/graduate-party', [GraduatePartyController::class, 'createGraduateParty'])->name('store.graduate');


});


require __DIR__.'/auth.php';
