<?php

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
    return redirect('home');
});
//Route::get('/boms', 'App\Http\Controllers\BomController@index');
Route::get('/boms', [App\Http\Controllers\BomController::class, 'index'])->name('boms');
Route::get('/parts/show', 'App\Http\Controllers\BomController@index');
Route::get('/boms/showParts/{partID}/{semiPartVersion}', 'App\Http\Controllers\BomController@showParts')->name('vvv');;

// Route::resource('parts', 'App\Http\Controllers\PartController');

Route::post('/boms/compare-boms', 'App\Http\Controllers\BomController@compareBoms')->name('compare-boms');
Route::get('/boms/compare-boms', 'App\Http\Controllers\BomController@showCompareForm')->name('compare-boms');
Route::get('/boms/import', 'App\Http\Controllers\BomController@importForm')->name('boms.import.form');
//Route::post('/boms/import', 'App\Http\Controllers\BomController@import')->name('boms.import');

Route::post('/boms/import', [App\Http\Controllers\BomController::class, 'import'])->name('boms.import');
//Route::delete('/boms', [App\Http\Controllers\BomController::class, 'destroy'])->name('boms.destroy');
//Route::delete('boms/{id}', [App\Http\Controllers\BomController::class, 'destroy']);
Route::resource('boms', 'App\Http\Controllers\BomController');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');