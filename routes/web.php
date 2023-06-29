<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProtecionCelularController;
use App\Http\Controllers\PIFController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes(['register' => false]);

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::post('/seleccionar-tienda', [IndexController::class, 'seleccionarTienda'])->name('seleccionar-tienda');
Route::get('/{tienda}/dashboard', [IndexController::class, 'dashboard'])->name('dashboard');

/* ------- Protecion Celular ------- */
Route::get('/{tienda}/pc', [ProtecionCelularController::class, 'index'])->name('pc');
Route::get('/{tienda}/{ambiente}/pc/getCampanias', [ProtecionCelularController::class, 'getCampanias'])->name('pc-getCampanias');
Route::get('/{tienda}/pc/nueva-campania', [ProtecionCelularController::class, 'create'])->name('pc-nueva-campania');
Route::post('/{tienda}/pc/guardar-campania', [ProtecionCelularController::class, 'store'])->name('pc-guardar-campania');
Route::get('/{tienda}/pc/editar-campania/{id}', [ProtecionCelularController::class, 'edit'])->name('pc-editar-campania');
Route::post('/{tienda}/pc/actualizar-campania', [ProtecionCelularController::class, 'update'])->name('pc-actualizar-campania');


/* ------- Proteccion Integral Familiar ------- */
Route::get('/{tienda}/pif', [PIFController::class, 'index'])->name('pif');
Route::get('/{tienda}/{ambiente}/pif/getCampanias', [PIFController::class, 'getCampanias'])->name('pif-getCampanias');
Route::get('/{tienda}/pif/nueva-campania', [PIFController::class, 'create'])->name('pif-nueva-campania');
Route::post('/{tienda}/pif/guardar-campania', [PIFController::class, 'store'])->name('pif-guardar-campania');
Route::get('/{tienda}/pif/editar-campania/{id}', [PIFController::class, 'edit'])->name('pif-editar-campania');
Route::post('/{tienda}/pif/actualizar-campania', [PIFController::class, 'update'])->name('pif-actualizar-campania');