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

    Route::post('store/student', [\App\Http\Controllers\StudentsController::class, 'store'])->name('store.student');

    Route::get('admin/menu-especial', [\App\Http\Controllers\MenuController::class, 'indexSpecialMenu'])->name('menuEspecial');

    Route::post('store/menu-especial', [\App\Http\Controllers\MenuController::class, 'storeSpecialMenu'])->name('store.menuEspecial');

    Route::put('edit/menu/{menu}', [\App\Http\Controllers\MenuController::class, 'edit'])->name('edit.menu');

    Route::put('edit/school/{school}', [\App\Http\Controllers\SchoolsController::class, 'edit'])->name('edit.school');

    Route::put('edit/payment/{paymentType}', [\App\Http\Controllers\FormasPagoController::class, 'edit'])->name('edit.payment');

    Route::put('edit/days/{day}', [\App\Http\Controllers\DaysController::class, 'edit'])->name('edit.day');

    Route::put('edit/special-menu/{menu}', [\App\Http\Controllers\MenuController::class, 'editSpecialMenu'])->name('edit.specialMenu');

    Route::put('edit/graduate/{graduate}', [\App\Http\Controllers\StudentsController::class, 'edit'])->name('edit.egresado');

    Route::get('/admin/estudiante/{student}', [\App\Http\Controllers\StudentsController::class, 'showStudent'])->name
    ('show.student');

    Route::post('/admin/pay-debt/{student}', [\App\Http\Controllers\StudentsController::class, 'payPartOfDebt'])->name('payDebt');

    Route::delete('/admin/eliminar-estudiante/{student}', [\App\Http\Controllers\StudentsController::class, 'deleteStudent'])->name('deleteStudent');

    Route::delete('/admin/eliminar-evento/{event}', [\App\Http\Controllers\GraduatePartyController::class, 'deleteEvent'])->name('deleteEvent');

    Route::get('/admin/intereses-cuotas', [\App\Http\Controllers\InteresCuotaController::class, 'index'])->name('interesCuotas');

    Route::post('/admin/editar-interes', [\App\Http\Controllers\InteresCuotaController::class, 'edit'])->name('edit.interes');

    Route::put('/admin/editar-evento/{event}', [GraduatePartyController::class, 'edit'])->name('edit.graduate');

    Route::get('/admin/eventos-terminados', [GraduatePartyController::class, 'finishedEvents'])->name('finished.events');

    Route::get('/admin/eliminar-familia/{family}', [\App\Http\Controllers\StudentsController::class, 'deleteFamily'])->name('deleteFamily');

    Route::post('/admin/editar-familia/{family}', [\App\Http\Controllers\StudentsController::class, 'editFamily'])->name('editFamily');

    Route::post('/admin/store-invitado', [\App\Http\Controllers\StudentsController::class, 'storeFamily'])->name('store.invitado');

    Route::post('/admin/create-discount/{event}', [GraduatePartyController::class, 'createDiscount'])->name('create.discount');

    Route::put('/admin/edit-discount/{event}', [GraduatePartyController::class, 'editDiscount'])->name('edit.discount');

    Route::post('/admin/create-student-discount/{student}', [\App\Http\Controllers\StudentsController::class, 'createDiscount'])->name('create.studentDiscount');

    Route::get('/admin/descuentos-egresados', [\App\Http\Controllers\DescuentosCantidadegresadosController::class, 'index']);

    Route::put('/admin/descuentos-egresados-editar', [\App\Http\Controllers\DescuentosCantidadegresadosController::class, 'edit'])->name('edit.discounts');

    //** listados de evento */
    Route::get('/admin/listar/{event}', [GraduatePartyController::class, 'showStudentsList'])->name('list.event');
});


require __DIR__.'/auth.php';
