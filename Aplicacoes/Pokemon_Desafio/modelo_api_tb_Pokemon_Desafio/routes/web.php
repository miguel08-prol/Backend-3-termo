<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\PokemonController;
use App\Http\Controllers\CustomPokemonController;
use App\Helpers\TypeHelper;
use App\Http\Controllers\BattleController;

Route::get('/battle', [BattleController::class, 'index'])->name('battle.index');
Route::get('/battle/compare/{id1}/{id2}', [BattleController::class, 'compare'])->name('battle.compare');
Route::get('/battle/search', [BattleController::class, 'search'])->name('battle.search');
Route::get('/api/pokemon/{id}', [BattleController::class, 'getPokemonData'])->name('battle.get-pokemon');
Route::get('/custom/pokemons/search', [CustomPokemonController::class, 'search'])->name('custom-pokemons.search-api');

// ==================== ROTA PRINCIPAL ====================
Route::get('/', function () {
    return view('welcome');
});

// ==================== ROTAS DE IMAGEM ====================
Route::get('/image/{path}', function ($path) {
    try {
        $fullPath = storage_path('app/public/' . $path);
        
        if (!file_exists($fullPath)) {
            return redirect('https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/25.png');
        }
        
        $content = file_get_contents($fullPath);
        $mimeType = mime_content_type($fullPath);
        
        return response($content, 200)
            ->header('Content-Type', $mimeType)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Cache-Control', 'public, max-age=86400');
    } catch (\Exception $e) {
        return redirect('https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/25.png');
    }
})->where('path', '.*')->name('image.serve');

// ==================== ROTAS DE POKÉMON CUSTOMIZADOS ====================
Route::prefix('custom')->group(function () {
    Route::get('/pokemons', [CustomPokemonController::class, 'index'])->name('custom-pokemons.index');
    Route::get('/pokemons/create', [CustomPokemonController::class, 'create'])->name('custom-pokemons.create');
    Route::post('/pokemons', [CustomPokemonController::class, 'store'])->name('custom-pokemons.store');
    Route::get('/pokemons/{customPokemon}', [CustomPokemonController::class, 'show'])->name('custom-pokemons.show');
    Route::get('/pokemons/{customPokemon}/edit', [CustomPokemonController::class, 'edit'])->name('custom-pokemons.edit');
    Route::put('/pokemons/{customPokemon}', [CustomPokemonController::class, 'update'])->name('custom-pokemons.update');
    Route::delete('/pokemons/{customPokemon}', [CustomPokemonController::class, 'destroy'])->name('custom-pokemons.destroy');
    Route::get('/search', [CustomPokemonController::class, 'search'])->name('custom-pokemons.search');
});

// ==================== ROTAS DE GERAÇÃO DE IMAGEM ====================
Route::post('/custom/generate-image', [CustomPokemonController::class, 'generateImage'])->name('custom-pokemons.generate-image');
Route::post('/custom/generate-image-python', [CustomPokemonController::class, 'generateImageWithPython'])->name('custom-pokemons.generate-image-python');

// ==================== API ROTAS ====================
Route::get('/api/custom-pokemons', [CustomPokemonController::class, 'indexJson'])->name('api.custom-pokemons');

// API de análise de tipos
Route::post('/api/type-analysis', function(Request $request) {
    try {
        $types = $request->input('types', []);
        
        if (empty($types)) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhum tipo fornecido'
            ]);
        }
        
        $typeData = TypeHelper::getWeaknessesAndResistances($types);
        $strongAgainst = TypeHelper::getStrongAgainst($types);
        
        return response()->json([
            'success' => true,
            'weaknesses' => $typeData['weaknesses'],
            'resistances' => $typeData['resistances'],
            'immunities' => $typeData['immunities'],
            'strongAgainst' => $strongAgainst
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
});

// ==================== ROTAS DE POKÉMON OFICIAIS ====================
Route::get('/pokemon/{idOrName}', [PokemonController::class, 'index'])->name('pokemon.show');
Route::get('/pokemon-id/{id}', [PokemonController::class, 'getPokemonById'])->name('pokemon.by-id');
Route::get('/api/pokemon/{query}', [PokemonController::class, 'apiSearch'])->name('api.pokemon.search');

// API externa (legado)
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