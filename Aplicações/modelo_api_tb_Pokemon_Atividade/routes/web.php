<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\PokemonController;

Route::get('/pokemon-card', [PokemonController::class, 'index']);

Route::get('/pokemon/{idOrName}', [PokemonController::class, 'index'])->name('pokemon.show');

// Exemplo 1: Get
route::get('pokemon/{nome}',function ($nome) {
    $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$nome}");

    if($response->successful()) {
        $dados = $response->json();
        return response()->json([
            'status' => 'Conectado com sucesso',
            'resultado' => [
                'identificador' => $dados[ 'id'],
                'nome_do_pokemon' => ucfirst($dados['name']),
                'foto' => $dados['sprites']['front_default']
            ]
        ], 200);
    }
    return response()->json(['erro' => 'Pokemon não encontrado'], 404);
});

// Exemplo 2: Post
Route::post('pokemon/novo', function(Request $request) {
    $dados  = $request->validate([
        'nome' => 'required|string|min:3',
        'tipo' => 'required|string',
        'ataque' => 'required|integer',
    ]);

    return response() ->json([
        'mensagem' => 'Pokemon cadastrado com sucesso!',
        'id_gerado' => rand(1000, 9999),
        'dados_recebidos' => $dados
    ], 201);
    
    });

    // Ativida

// Exemplo 1: Get
// Route::get('usuario/{id}', function ($id) {
//     $response = Http::get("https://dummyjson.com/users/{$id}");


//     if ($response->successful()) {
//         $dados = $response->json();
        
//         return response()->json([
//             'status' => 'Conectado com sucesso',
//             'resultado' => [
//                 'identificador' => $dados['id'],
//                 'nome_usuario'  => ucfirst($dados['firstName']) . ' ' . ucfirst($dados['lastName']),
//                 'foto'          => $dados['image'],
//                 'empresa'       => $dados['company']['name'] ?? 'Não informado'
//             ]
//         ], 200);
//     }

//     return response()->json(['erro' => 'Usuário não encontrado'], 404);
// });

// // Exemplo 2: POST
// Route::post('usuario/novo', function(Request $request) {
//     $dados = $request->validate([
//         'nome'      => 'required|string|min:3',
//         'email'     => 'required|email',
//         'profissao' => 'required|string',
//     ]);

//     return response()->json([
//         'mensagem'        => 'Usuário cadastrado com sucesso!',
//         'id_gerado'       => rand(1000, 9999),
//         'dados_recebidos' => $dados
//     ], 201);
// });




Route::get('/', function () {
    return view('welcome');
});
