<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjetoController;
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


Route::prefix('/projeto')->group(function () {
    Route::get('/lista', [ProjetoController::class, 'listar'])->name('lista');
    Route::get('/formulario/{id?}', [ProjetoController::class, 'formulario'])->name('formulario');
    Route::post('/novo', [ProjetoController::class, 'novo']);
    Route::delete('/deletar/{id}', [ProjetoController::class, 'deletar']);
    Route::put('/editar/{id}', [ProjetoController::class, 'editar']);
});

Route::prefix('/tarefa')->group(function () {
    Route::get('/busca/{projeto_id}', [ProjetoController::class, 'buscarTarefas']);
    Route::post('/novo', [ProjetoController::class, 'novaTarefa']);
    Route::delete('/deletar/{id}', [ProjetoController::class, 'deletarTarefa']);
    Route::put('/editar/{id}', [ProjetoController::class, 'editarTarefa']);
});

Route::get('/{any}', [ProjetoController::class, 'index'])->where('any', '.*');


