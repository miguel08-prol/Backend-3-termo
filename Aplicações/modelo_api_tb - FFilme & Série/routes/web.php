<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmeController;
use App\Http\Controllers\StreamController;

// Rota Principal (HOME) - Esta é a que deve valer!
Route::get('/', [StreamController::class, 'index'])->name('home');

// Rota de Detalhes (Filme ou Série)
Route::get('/detalhes/{type}/{id}', [StreamController::class, 'show'])->name('detalhes');

// Outras rotas que você já tinha
Route::get('/filmes', [FilmeController::class, 'index'])->name('filmes.index');
Route::get('/filme/{idOrName?}', [FilmeController::class, 'index'])->name('filmes.show');

// Rota para o catálogo de Séries
Route::get('/series', [StreamController::class, 'seriesIndex'])->name('series.index');