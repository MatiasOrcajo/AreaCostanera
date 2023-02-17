<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function(){
    Route::get('/admin/dashboard', [GraduatePartyController::class, 'index'])->name('dashboard');
    Route::get('/admin/escuelas', [\App\Http\Controllers\SchoolsController::class, 'index'])->name('schools');
    Route::post('store/school', [\App\Http\Controllers\SchoolsController::class, 'storeSchool'])->name('store.school');

});


require __DIR__.'/auth.php';
