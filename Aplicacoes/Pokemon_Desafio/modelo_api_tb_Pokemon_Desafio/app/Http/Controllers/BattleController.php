<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\CustomPokemon;

class BattleController extends Controller
{
    /**
     * Página principal da batalha
     */
    public function index()
    {
        return view('battle.index');
    }

    /**
     * Busca Pokémon por nome (para o autocomplete)
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('q');
            
            if (strlen($query) < 2) {
                return response()->json([]);
            }
            
            $results = [];
            
            // 1. Buscar em Pokémon customizados
            $customPokemons = CustomPokemon::where('name', 'like', "%{$query}%")
                ->limit(10)
                ->get();
            
            foreach ($customPokemons as $p) {
                $results[] = [
                    'id' => 'custom_' . $p->id,
                    'name' => $p->name,
                    'is_custom' => true,
                    'image_url' => $this->getImageUrl($p->image_url),
                ];
            }
            
            // 2. Buscar Pokémon oficiais na API
            $officialResponse = Http::get("https://pokeapi.co/api/v2/pokemon?limit=1000");
            
            if ($officialResponse->successful()) {
                $allPokemon = $officialResponse->json()['results'];
                
                // Filtrar por nome
                $filtered = array_filter($allPokemon, function($p) use ($query) {
                    return str_contains(strtolower($p['name']), strtolower($query));
                });
                
                // Pegar os primeiros 20 resultados
                $filtered = array_slice($filtered, 0, 20);
                
                foreach ($filtered as $p) {
                    // Buscar detalhes em paralelo
                    $details = Http::get($p['url'])->json();
                    
                    if ($details) {
                        $results[] = [
                            'id' => $details['id'],
                            'name' => $details['name'],
                            'is_custom' => false,
                            'image_url' => $details['sprites']['other']['official-artwork']['front_default'] 
                                        ?? $details['sprites']['front_default'],
                        ];
                    }
                }
            }
            
            // Limitar a 30 resultados
            $results = array_slice($results, 0, 30);
            
            return response()->json($results);
            
        } catch (\Exception $e) {
            \Log::error('Erro na busca: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Página de comparação/resultado da batalha
     */
    public function compare($id1, $id2)
    {
        try {
            $pokemon1 = $this->getPokemonDataById($id1);
            $pokemon2 = $this->getPokemonDataById($id2);
            
            if (!$pokemon1 || !$pokemon2) {
                return redirect()->route('battle.index')->with('error', 'Pokémon não encontrado');
            }
            
            // Simular batalha
            $battleResult = $this->simulateBattle($pokemon1, $pokemon2);
            
            // Preparar dados para o gráfico radar
            $comparisonData = $this->prepareRadarData($pokemon1, $pokemon2);
            
            // Cores dos tipos
            $typeColors = [
                'normal' => '#A8A878', 'fire' => '#F08030', 'water' => '#6890F0', 'electric' => '#F8D030',
                'grass' => '#78C850', 'ice' => '#98D8D8', 'fighting' => '#C03028', 'poison' => '#A040A0',
                'ground' => '#E0C068', 'flying' => '#A890F0', 'psychic' => '#F85888', 'bug' => '#A8B820',
                'rock' => '#B8A038', 'ghost' => '#705898', 'dragon' => '#7038F8', 'dark' => '#705848',
                'steel' => '#B8B8D0', 'fairy' => '#EE99AC'
            ];
            
            return view('battle.compare', compact('pokemon1', 'pokemon2', 'battleResult', 'comparisonData', 'typeColors'));
            
        } catch (\Exception $e) {
            \Log::error('Erro na comparação: ' . $e->getMessage());
            return redirect()->route('battle.index')->with('error', 'Erro ao comparar Pokémon');
        }
    }

    /**
     * Busca dados de um Pokémon por ID
     */
    public function getPokemonData($id)
    {
        try {
            $pokemon = $this->getPokemonDataById($id);
            
            if (!$pokemon) {
                return response()->json(['error' => 'Pokémon não encontrado'], 404);
            }
            
            return response()->json($pokemon);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar Pokémon: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtém dados do Pokémon por ID (privado)
     */
    private function getPokemonDataById($id)
    {
        // Verificar se é customizado
        if (str_starts_with($id, 'custom_')) {
            $customId = str_replace('custom_', '', $id);
            $custom = CustomPokemon::find($customId);
            
            if ($custom) {
                return $this->formatCustomPokemon($custom);
            }
        }
        
        // Buscar na API oficial
        $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$id}");
        
        if (!$response->successful()) {
            return null;
        }
        
        $data = $response->json();
        return $this->formatOfficialPokemon($data);
    }

    /**
     * Formata Pokémon oficial
     */
    private function formatOfficialPokemon($data)
    {
        return [
            'id' => $data['id'],
            'name' => $data['name'],
            'height' => $data['height'],
            'weight' => $data['weight'],
            'base_experience' => $data['base_experience'],
            'stats' => $data['stats'],
            'types' => array_map(function($t) { 
                return $t['type']['name']; 
            }, $data['types']),
            'image_url' => $data['sprites']['other']['official-artwork']['front_default'] 
                        ?? $data['sprites']['front_default'],
            'is_custom' => false,
        ];
    }

    /**
     * Formata Pokémon customizado
     */
  private function formatCustomPokemon($custom)
{
    $imageUrl = $custom->image_url;
    if ($imageUrl && !str_starts_with($imageUrl, 'http')) {
        $imageUrl = asset('storage/' . $imageUrl);
    }
    
    $types = $custom->types;
    if (is_string($types)) {
        $types = json_decode($types, true);
    }
    
    $stats = $custom->stats;
    if (is_string($stats)) {
        $stats = json_decode($stats, true);
    }
    
    // Processar evoluções
    $evolutions = $custom->evolutions;
    if (is_string($evolutions)) {
        $evolutions = json_decode($evolutions, true);
    }
    
    // Formatar evoluções para o frontend
    $formattedEvolutions = [];
    if (is_array($evolutions)) {
        foreach ($evolutions as $evo) {
            $evoImage = $evo['image'] ?? null;
            if ($evoImage && !str_starts_with($evoImage, 'http')) {
                $evoImage = asset('storage/' . $evoImage);
            }
            $formattedEvolutions[] = [
                'name' => $evo['name'] ?? '',
                'image' => $evoImage,
            ];
        }
    }
    
    return [
        'id' => 'custom_' . $custom->id,
        'name' => $custom->name,
        'height' => $custom->height ?? 17,
        'weight' => $custom->weight ?? 905,
        'base_experience' => $custom->base_experience ?? 100,
        'stats' => $stats ?? [],
        'types' => $types ?? [],
        'image_url' => $imageUrl,
        'evolutions' => $formattedEvolutions,  // <--- ADICIONE ESTA LINHA
        'is_custom' => true,
    ];
}

    /**
     * Simula uma batalha entre dois Pokémon
     */
    private function simulateBattle($pokemon1, $pokemon2)
    {
        // Calcular poder base
        $power1 = $this->calculatePower($pokemon1);
        $power2 = $this->calculatePower($pokemon2);
        
        // Calcular efetividade de tipos
        $effectiveness1 = $this->calculateTypeEffectiveness($pokemon1['types'], $pokemon2['types']);
        $effectiveness2 = $this->calculateTypeEffectiveness($pokemon2['types'], $pokemon1['types']);
        
        // Fator sorte
        $luck1 = rand(85, 115) / 100;
        $luck2 = rand(85, 115) / 100;
        
        $finalPower1 = $power1 * $effectiveness1 * $luck1;
        $finalPower2 = $power2 * $effectiveness2 * $luck2;
        
        // Determinar vencedor
        $winner = $finalPower1 > $finalPower2 ? 1 : 2;
        
        // Calcular scores
        $score1 = min(100, ($power1 / 780) * 100);
        $score2 = min(100, ($power2 / 780) * 100);
        
        return [
            'winner' => $winner,
            'winner_name' => $winner == 1 ? $pokemon1['name'] : $pokemon2['name'],
            'effectiveness1' => $effectiveness1,
            'effectiveness2' => $effectiveness2,
            'score1' => $score1,
            'score2' => $score2,
            'difficulty' => abs($finalPower1 - $finalPower2) > 100 ? 'fácil' : 'equilibrada',
            'turns1' => rand(1, 10),
            'turns2' => rand(1, 10),
        ];
    }

    /**
     * Calcula poder baseado nos stats
     */
    private function calculatePower($pokemon)
    {
        $stats = $pokemon['stats'] ?? [];
        $total = 0;
        
        foreach ($stats as $stat) {
            if (is_array($stat)) {
                $total += $stat['base_stat'] ?? 0;
            }
        }
        
        return $total;
    }

    /**
     * Calcula efetividade de tipos
     */
    private function calculateTypeEffectiveness($attackTypes, $defenseTypes)
    {
        $typeChart = [
            'fire' => ['grass' => 2, 'ice' => 2, 'bug' => 2, 'steel' => 2, 'water' => 0.5, 'fire' => 0.5, 'rock' => 0.5, 'dragon' => 0.5],
            'water' => ['fire' => 2, 'ground' => 2, 'rock' => 2, 'water' => 0.5, 'grass' => 0.5, 'dragon' => 0.5],
            'grass' => ['water' => 2, 'ground' => 2, 'rock' => 2, 'fire' => 0.5, 'grass' => 0.5, 'poison' => 0.5, 'flying' => 0.5, 'bug' => 0.5, 'dragon' => 0.5, 'steel' => 0.5],
            'electric' => ['water' => 2, 'flying' => 2, 'grass' => 0.5, 'electric' => 0.5, 'dragon' => 0.5, 'ground' => 0],
            'fighting' => ['normal' => 2, 'ice' => 2, 'rock' => 2, 'dark' => 2, 'steel' => 2, 'poison' => 0.5, 'flying' => 0.5, 'psychic' => 0.5, 'bug' => 0.5, 'fairy' => 0.5, 'ghost' => 0],
            'psychic' => ['fighting' => 2, 'poison' => 2, 'psychic' => 0.5, 'dark' => 0, 'steel' => 0.5],
            'ice' => ['grass' => 2, 'ground' => 2, 'flying' => 2, 'dragon' => 2, 'fire' => 0.5, 'water' => 0.5, 'ice' => 0.5, 'steel' => 0.5],
            'dragon' => ['dragon' => 2, 'steel' => 0.5, 'fairy' => 0],
            'dark' => ['psychic' => 2, 'ghost' => 2, 'fighting' => 0.5, 'dark' => 0.5, 'fairy' => 0.5],
            'fairy' => ['fighting' => 2, 'dragon' => 2, 'dark' => 2, 'fire' => 0.5, 'poison' => 0.5, 'steel' => 0.5],
        ];
        
        $multiplier = 1;
        
        foreach ($attackTypes as $attackType) {
            foreach ($defenseTypes as $defenseType) {
                if (isset($typeChart[$attackType][$defenseType])) {
                    $multiplier *= $typeChart[$attackType][$defenseType];
                }
            }
        }
        
        return $multiplier;
    }

    /**
     * Prepara dados para gráfico radar
     */
    private function prepareRadarData($pokemon1, $pokemon2)
    {
        $stats1 = [];
        $stats2 = [];
        
        foreach ($pokemon1['stats'] as $stat) {
            if (is_array($stat)) {
                $stats1[$stat['stat']['name']] = $stat['base_stat'];
            }
        }
        
        foreach ($pokemon2['stats'] as $stat) {
            if (is_array($stat)) {
                $stats2[$stat['stat']['name']] = $stat['base_stat'];
            }
        }
        
        return [
            'labels' => ['HP', 'Ataque', 'Defesa', 'Atq. Especial', 'Def. Especial', 'Velocidade'],
            'datasets' => [
                [
                    'label' => ucfirst($pokemon1['name']),
                    'data' => [
                        $stats1['hp'] ?? 50,
                        $stats1['attack'] ?? 50,
                        $stats1['defense'] ?? 50,
                        $stats1['special-attack'] ?? 50,
                        $stats1['special-defense'] ?? 50,
                        $stats1['speed'] ?? 50,
                    ],
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'borderColor' => '#3b82f6',
                    'borderWidth' => 2,
                    'pointBackgroundColor' => '#3b82f6',
                ],
                [
                    'label' => ucfirst($pokemon2['name']),
                    'data' => [
                        $stats2['hp'] ?? 50,
                        $stats2['attack'] ?? 50,
                        $stats2['defense'] ?? 50,
                        $stats2['special-attack'] ?? 50,
                        $stats2['special-defense'] ?? 50,
                        $stats2['speed'] ?? 50,
                    ],
                    'backgroundColor' => 'rgba(239, 68, 68, 0.2)',
                    'borderColor' => '#ef4444',
                    'borderWidth' => 2,
                    'pointBackgroundColor' => '#ef4444',
                ]
            ]
        ];
    }

    /**
     * Obtém URL da imagem
     */
    private function getImageUrl($imageUrl)
    {
        if (!$imageUrl) return null;
        if (str_starts_with($imageUrl, 'http')) return $imageUrl;
        if (str_starts_with($imageUrl, 'storage/')) {
            return asset($imageUrl);
        }
        return asset('storage/' . $imageUrl);
    }
}