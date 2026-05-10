<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Helpers\TypeHelper;

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

        // Buscar na PokeAPI
        $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$search}");

        // Se não encontrou, buscar aproximado
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
            
            // Buscar espécie para obter descrição
            $speciesData = Http::get($pokemon['species']['url'])->json();
            
            // Buscar descrição em português ou inglês
            $description = '';
            if (isset($speciesData['flavor_text_entries'])) {
                // Tentar português primeiro
                foreach ($speciesData['flavor_text_entries'] as $entry) {
                    if ($entry['language']['name'] === 'pt') {
                        $description = $entry['flavor_text'];
                        break;
                    }
                }
                // Se não tiver português, pegar inglês
                if (!$description) {
                    foreach ($speciesData['flavor_text_entries'] as $entry) {
                        if ($entry['language']['name'] === 'en') {
                            $description = $entry['flavor_text'];
                            break;
                        }
                    }
                }
            }
            
            // Limpar descrição
            $description = str_replace(['\n', '\f', "\n", "\r"], ' ', $description);
            $description = preg_replace('/\s+/', ' ', $description);
            $pokemon['description'] = trim($description);
            
            // Buscar dados de evolução
            $evolutionData = Http::get($speciesData['evolution_chain']['url'])->json();

            $evolucaoDetalhes = [];
            $this->extractEvolutionChain($evolutionData['chain'], $evolucaoDetalhes);
            
            $pokemon['is_custom'] = false;
            
            // Adicionar tipos formatados para o helper
            $pokemon['types_formatted'] = array_map(function($type) {
                return $type['type']['name'];
            }, $pokemon['types']);

            return view('pokemon', compact('pokemon', 'evolucaoDetalhes'));
        }

        return redirect()->route('pokemon.show', 'pikachu');
    }

    /**
     * Extrai a cadeia de evolução recursivamente
     */
    private function extractEvolutionChain($chain, &$evolucaoDetalhes)
    {
        $nomeEvo = $chain['species']['name'];
        $resEvo = Http::get("https://pokeapi.co/api/v2/pokemon/{$nomeEvo}")->json();
        
        if ($resEvo) {
            $evolucaoDetalhes[] = [
                'id' => $resEvo['id'],
                'nome' => $nomeEvo,
                'foto' => $resEvo['sprites']['other']['official-artwork']['front_default'] 
                         ?? $resEvo['sprites']['front_default']
            ];
        }
        
        if (!empty($chain['evolves_to'])) {
            $this->extractEvolutionChain($chain['evolves_to'][0], $evolucaoDetalhes);
        }
    }

    /**
     * Busca Pokémon por ID para navegação (anterior/próximo)
     */
    public function getPokemonById($id)
    {
        // Verificar se é um Pokémon customizado
        $customPokemon = \App\Models\CustomPokemon::find($id);
        if ($customPokemon) {
            return redirect()->route('custom-pokemons.show', $customPokemon);
        }
        
        // Buscar Pokémon oficial pelo ID
        $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$id}");
        
        if ($response->successful()) {
            $pokemon = $response->json();
            
            // Buscar espécie para obter descrição
            $speciesData = Http::get($pokemon['species']['url'])->json();
            
            // Buscar descrição
            $description = '';
            if (isset($speciesData['flavor_text_entries'])) {
                foreach ($speciesData['flavor_text_entries'] as $entry) {
                    if ($entry['language']['name'] === 'pt') {
                        $description = $entry['flavor_text'];
                        break;
                    }
                }
                if (!$description) {
                    foreach ($speciesData['flavor_text_entries'] as $entry) {
                        if ($entry['language']['name'] === 'en') {
                            $description = $entry['flavor_text'];
                            break;
                        }
                    }
                }
            }
            
            $description = str_replace(['\n', '\f', "\n", "\r"], ' ', $description);
            $description = preg_replace('/\s+/', ' ', $description);
            $pokemon['description'] = trim($description);
            
            // Buscar dados de evolução
            $evolutionData = Http::get($speciesData['evolution_chain']['url'])->json();
            
            $evolucaoDetalhes = [];
            $this->extractEvolutionChain($evolutionData['chain'], $evolucaoDetalhes);
            
            $pokemon['is_custom'] = false;
            $pokemon['types_formatted'] = array_map(function($type) {
                return $type['type']['name'];
            }, $pokemon['types']);
            
            return view('pokemon', compact('pokemon', 'evolucaoDetalhes'));
        }
        
        return redirect()->route('pokemon.show', 'pikachu');
    }

    /**
     * API: Busca Pokémon oficial por ID ou nome (retorna JSON)
     */
    public function apiSearch($query)
    {
        $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$query}");
        
        if ($response->successful()) {
            $pokemon = $response->json();
            
            // Buscar espécie para descrição
            $speciesData = Http::get($pokemon['species']['url'])->json();
            
            $description = '';
            if (isset($speciesData['flavor_text_entries'])) {
                foreach ($speciesData['flavor_text_entries'] as $entry) {
                    if ($entry['language']['name'] === 'pt') {
                        $description = $entry['flavor_text'];
                        break;
                    }
                }
                if (!$description) {
                    foreach ($speciesData['flavor_text_entries'] as $entry) {
                        if ($entry['language']['name'] === 'en') {
                            $description = $entry['flavor_text'];
                            break;
                        }
                    }
                }
            }
            
            $description = str_replace(['\n', '\f', "\n", "\r"], ' ', $description);
            $description = preg_replace('/\s+/', ' ', $description);
            
            // Buscar evoluções
            $evolutionData = Http::get($speciesData['evolution_chain']['url'])->json();
            $evolucaoDetalhes = [];
            $this->extractEvolutionChain($evolutionData['chain'], $evolucaoDetalhes);
            
            return response()->json([
                'success' => true,
                'pokemon' => [
                    'id' => $pokemon['id'],
                    'name' => $pokemon['name'],
                    'height' => $pokemon['height'],
                    'weight' => $pokemon['weight'],
                    'base_experience' => $pokemon['base_experience'],
                    'description' => $description,
                    'types' => array_map(function($type) {
                        return $type['type']['name'];
                    }, $pokemon['types']),
                    'stats' => $pokemon['stats'],
                    'abilities' => $pokemon['abilities'],
                    'image_url' => $pokemon['sprites']['other']['official-artwork']['front_default'] ?? $pokemon['sprites']['front_default'],
                    'evolutions' => $evolucaoDetalhes
                ]
            ]);
            
        }
        
        return response()->json(['success' => false, 'message' => 'Pokémon não encontrado'], 404);
    }
}