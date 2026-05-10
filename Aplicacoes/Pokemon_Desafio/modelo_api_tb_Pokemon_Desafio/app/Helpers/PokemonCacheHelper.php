// app/Helpers/PokemonCacheHelper.php
<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PokemonCacheHelper
{
    /**
     * Busca todos os Pokémon oficiais (com cache)
     */
    public static function getAllOfficialPokemons()
    {
        // Cache por 24 horas para não sobrecarregar a API
        return Cache::remember('all_official_pokemons', 86400, function () {
            $allPokemons = [];
            $limit = 2000; // Buscar todos de uma vez (máximo da API é ~1300)
            
            $response = Http::get("https://pokeapi.co/api/v2/pokemon?limit={$limit}&offset=0");
            
            if (!$response->successful()) {
                return [];
            }
            
            $data = $response->json();
            $results = $data['results'] ?? [];
            
            // Buscar detalhes em paralelo (mais rápido)
            foreach ($results as $pokemon) {
                try {
                    $details = Http::get($pokemon['url'])->json();
                    
                    if ($details) {
                        $allPokemons[] = [
                            'id' => $details['id'],
                            'name' => $details['name'],
                            'image_url' => $details['sprites']['other']['official-artwork']['front_default'] 
                                        ?? $details['sprites']['front_default'],
                            'types' => array_map(function($t) { 
                                return $t['type']['name']; 
                            }, $details['types']),
                            'is_custom' => false
                        ];
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
            
            return $allPokemons;
        });
    }
    
    /**
     * Busca Pokémon por nome na API oficial (busca individual)
     */
    public static function searchPokemonByName($name)
    {
        $allPokemons = self::getAllOfficialPokemons();
        
        // Busca exata primeiro
        $exact = collect($allPokemons)->first(function($p) use ($name) {
            return strtolower($p['name']) === strtolower($name);
        });
        
        if ($exact) {
            return [$exact];
        }
        
        // Busca por similaridade
        return collect($allPokemons)
            ->filter(function($p) use ($name) {
                return str_contains(strtolower($p['name']), strtolower($name));
            })
            ->values()
            ->toArray();
    }
}