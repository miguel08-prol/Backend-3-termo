<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PokemonController extends Controller
{
 public function index($idOrName = null)
{
    // Se não houver pesquisa, sorteia um ID
    if (!$idOrName) {
        $idOrName = rand(1, 151);
    }

    $search = strtolower(trim($idOrName));

    // Primeiro, tentar buscar nos Pokémon customizados
    $customPokemon = \App\Models\CustomPokemon::where('name', 'like', "%{$search}%")->first();
    
    if ($customPokemon) {
        return redirect()->route('custom-pokemons.show', $customPokemon);
    }

    // Se não encontrou nos customizados, buscar na API
    $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$search}");

    if (!$response->successful()) {
        $listResponse = Http::get("https://pokeapi.co/api/v2/pokemon?limit=151")->json();
        $nomes = collect($listResponse['results'])->pluck('name');

        $aproximado = $nomes->first(fn($nome) => str_contains($nome, $search));

        if (!$aproximado) {
            $aproximado = $nomes->sortBy(fn($nome) => levenshtein($search, $nome))->first();
        }

        $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$aproximado}");
    }

    if ($response->successful()) {
        $pokemon = $response->json();
        
        $speciesData = Http::get($pokemon['species']['url'])->json();
        $evolutionData = Http::get($speciesData['evolution_chain']['url'])->json();

        $evolucaoDetalhes = [];
        $atual = $evolutionData['chain'];

        do {
            $nomeEvo = $atual['species']['name'];
            $resEvo = Http::get("https://pokeapi.co/api/v2/pokemon/{$nomeEvo}")->json();
            
            if ($resEvo) {
                $evolucaoDetalhes[] = [
                    'id' => $resEvo['id'],
                    'nome' => $nomeEvo,
                    'foto' => $resEvo['sprites']['other']['official-artwork']['front_default']
                ];
            }
            $atual = $atual['evolves_to'][0] ?? null;
        } while ($atual);
        
        $pokemon['is_custom'] = false;

        return view('pokemon', compact('pokemon', 'evolucaoDetalhes'));
    }

    return redirect()->route('pokemon.show', 'pikachu');
}


}