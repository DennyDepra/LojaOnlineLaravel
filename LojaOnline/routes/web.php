<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;

Route::get('/', [ProdutoController::class, 'index'])
    ->name('produtos.index');

Route::post('/produtos', [ProdutoController::class, 'store'])
    ->name('produtos.store');

Route::put('/produtos/{produto}', [ProdutoController::class, 'update'])
    ->name('produtos.update');

Route::delete('/produtos/{produto}', [ProdutoController::class, 'destroy'])
    ->name('produtos.destroy');
