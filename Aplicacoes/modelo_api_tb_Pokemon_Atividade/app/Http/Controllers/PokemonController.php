<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PokemonController extends Controller
{
    public function index($idOrName = null)
    {
        // 1. Se não houver pesquisa, sorteia um ID
        if (!$idOrName) {
            $idOrName = rand(1, 151);
        }

        $search = strtolower(trim($idOrName));

        // 2. Tenta a busca direta primeiro
        $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$search}");

        // 3. Se falhar (nome incompleto ou errado), tentamos aproximar
        if (!$response->successful()) {
            // Buscamos a lista dos 151 primeiros para comparar
            $listResponse = Http::get("https://pokeapi.co/api/v2/pokemon?limit=151")->json();
            $nomes = collect($listResponse['results'])->pluck('name');

            // Tenta encontrar um nome que CONTÉM o que o usuário digitou
            $aproximado = $nomes->first(fn($nome) => str_contains($nome, $search));

            // Se não achou por conter, tenta pelo som/escrita (Levenshtein)
            if (!$aproximado) {
                $aproximado = $nomes->sortBy(fn($nome) => levenshtein($search, $nome))->first();
            }

            // Se achou um nome próximo, faz a busca com ele
            $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$aproximado}");
        }

        if ($response->successful()) {
            $pokemon = $response->json();
            
            // Busca Espécie e Evolução
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

            return view('pokemon', compact('pokemon', 'evolucaoDetalhes'));
        }

        // Se mesmo assim nada der certo, volta para o início
        return redirect()->route('pokemon.index');
    }
}