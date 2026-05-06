<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\PokemonController;
use App\Http\Controllers\CustomPokemonController;

// Rota para API de customizados
// Adicione no arquivo web.php, dentro do grupo de rotas custom
Route::get('/api/custom-pokemons', [CustomPokemonController::class, 'indexJson']);
// Rota para servir imagens do storage (quando o link simbólico falha)
Route::get('/storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (file_exists($fullPath)) {
        return response()->file($fullPath);
    }
    abort(404);
})->where('path', '.*');

// Rotas para Pokémon Customizados (TODAS com 'custom-pokemons')
Route::prefix('custom')->group(function () {
    Route::get('/pokemons', [CustomPokemonController::class, 'index'])->name('custom-pokemons.index');
    Route::get('/pokemons/create', [CustomPokemonController::class, 'create'])->name('custom-pokemons.create');
    Route::post('/pokemons', [CustomPokemonController::class, 'store'])->name('custom-pokemons.store');
    Route::get('/pokemons/{customPokemon}', [CustomPokemonController::class, 'show'])->name('custom-pokemons.show');
    Route::get('/pokemons/{customPokemon}/edit', [CustomPokemonController::class, 'edit'])->name('custom-pokemons.edit');
    Route::put('/pokemons/{customPokemon}', [CustomPokemonController::class, 'update'])->name('custom-pokemons.update');
    Route::delete('/pokemons/{customPokemon}', [CustomPokemonController::class, 'destroy'])->name('custom-pokemons.destroy');
    Route::post('/generate-image', [CustomPokemonController::class, 'generateImage'])->name('custom-pokemons.generate-image');
});

// Rota principal da Pokédex
Route::get('/pokemon/{idOrName}', [PokemonController::class, 'index'])->name('pokemon.show');
Route::get('/pokemon-card', [PokemonController::class, 'index']);

// API externa - GET
Route::get('pokemon/{nome}', function ($nome) {
    $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$nome}");

    if($response->successful()) {
        $dados = $response->json();
        return response()->json([
            'status' => 'Conectado com sucesso',
            'resultado' => [
                'identificador' => $dados['id'],
                'nome_do_pokemon' => ucfirst($dados['name']),
                'foto' => $dados['sprites']['front_default']
            ]
        ], 200);
    }
    return response()->json(['erro' => 'Pokemon não encontrado'], 404);
});

// API externa - POST
Route::post('pokemon/novo', function(Request $request) {
    $dados = $request->validate([
        'nome' => 'required|string|min:3',
        'tipo' => 'required|string',
        'ataque' => 'required|integer',
    ]);

    return response()->json([
        'mensagem' => 'Pokemon cadastrado com sucesso!',
        'id_gerado' => rand(1000, 9999),
        'dados_recebidos' => $dados
    ], 201);
});

Route::get('/', function () {
    return view('welcome');
});