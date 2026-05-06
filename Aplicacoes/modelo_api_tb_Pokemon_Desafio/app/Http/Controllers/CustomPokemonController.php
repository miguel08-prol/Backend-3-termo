<?php
// app/Http/Controllers/CustomPokemonController.php

namespace App\Http\Controllers;

use App\Models\CustomPokemon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class CustomPokemonController extends Controller
{
public function index(Request $request)
{
    try {
        // Se for requisição AJAX/JSON, retorna dados para a API
        if ($request->wantsJson() || $request->get('format') === 'json') {
            $pokemons = CustomPokemon::all(); // Remove orderBy
            
            $formattedPokemons = [];
            foreach ($pokemons as $p) {
                $imageUrl = $p->image_url;
                if ($imageUrl) {
                    if (str_starts_with($imageUrl, 'storage/')) {
                        $imageUrl = asset($imageUrl);
                    } elseif (!str_starts_with($imageUrl, 'http')) {
                        $imageUrl = asset('storage/' . $imageUrl);
                    }
                }
                
                $formattedPokemons[] = [
                    'id' => $p->id,
                    'name' => $p->name,
                    'image_url' => $imageUrl ?? 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/0.png',
                    'types' => $p->types ?? [],
                    'is_custom' => true,
                    'created_at' => $p->created_at
                ];
            }
            
            // Ordenar em PHP
            usort($formattedPokemons, function($a, $b) {
                if (!$a['created_at'] && !$b['created_at']) return 0;
                if (!$a['created_at']) return 1;
                if (!$b['created_at']) return -1;
                return $b['created_at']->timestamp - $a['created_at']->timestamp;
            });
            
            return response()->json(['pokemons' => $formattedPokemons]);
        }
        
        $pokemons = CustomPokemon::all(); // Remove orderBy
        // Ordenar em PHP
        $pokemons = $pokemons->sortByDesc('created_at');
        
        return view('custom-pokemon.index', compact('pokemons'));
        
    } catch (\Exception $e) {
        \Log::error('Erro no index: ' . $e->getMessage());
        
        if ($request->wantsJson() || $request->get('format') === 'json') {
            return response()->json(['error' => 'Erro ao carregar Pokémon'], 500);
        }
        
        return view('custom-pokemon.index', ['pokemons' => collect([]), 'error' => $e->getMessage()]);
    }
}

    public function create()
    {
        return view('custom-pokemon.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'height' => 'nullable|numeric',
                'weight' => 'nullable|numeric',
                'base_experience' => 'nullable|integer',
                'image' => 'nullable|image|mimes:png,jpg,jpeg,gif|max:20480',
                'image_url' => 'nullable|string',
                'stats' => 'nullable|string',
                'abilities' => 'nullable|string',
                'evolutions' => 'nullable|string',
                'types' => 'nullable|string',
            ]);

            $stats = is_string($request->stats) ? json_decode($request->stats, true) : ($request->stats ?? []);
            $abilities = is_string($request->abilities) ? json_decode($request->abilities, true) : ($request->abilities ?? []);
            $evolutions = is_string($request->evolutions) ? json_decode($request->evolutions, true) : ($request->evolutions ?? []);
            $types = is_string($request->types) ? json_decode($request->types, true) : ($request->types ?? []);

            // Processar a imagem principal
            $imageUrl = $this->processImage($request);
            
            // Processar as imagens das evoluções
            $processedEvolutions = [];
            foreach ($evolutions as $evolution) {
                $evolutionImage = $evolution['image'] ?? null;
                
                // Se a imagem da evolução for Base64 (upload via preview)
                if ($evolutionImage && str_starts_with($evolutionImage, 'data:image')) {
                    $evolutionImage = $this->saveBase64Image($evolutionImage);
                }
                
                $processedEvolutions[] = [
                    'name' => $evolution['name'],
                    'image' => $evolutionImage
                ];
            }

            // Formatar stats
            $formattedStats = [];
            $statMappings = [
                'hp' => 'hp',
                'attack' => 'attack',
                'defense' => 'defense',
                'special-attack' => 'special-attack',
                'special-defense' => 'special-defense',
                'speed' => 'speed'
            ];

            foreach ($statMappings as $key => $statName) {
                $value = $stats[$key] ?? 50;
                $formattedStats[] = [
                    'stat' => ['name' => $statName],
                    'base_stat' => (int) $value
                ];
            }

            // Formatar habilidades
            $formattedAbilities = [];
            if (!empty($abilities)) {
                foreach ($abilities as $ability) {
                    if (!empty($ability['name'])) {
                        $formattedAbilities[] = [
                            'ability' => ['name' => $ability['name']],
                            'is_hidden' => isset($ability['is_hidden']) && $ability['is_hidden']
                        ];
                    }
                }
            }

            $customPokemon = CustomPokemon::create([
                'name' => $validated['name'],
                'height' => (int)($validated['height'] ?? 17),
                'weight' => (int)($validated['weight'] ?? 905),
                'base_experience' => (int)($validated['base_experience'] ?? 100),
                'image_url' => $imageUrl,
                'stats' => $formattedStats,
                'abilities' => $formattedAbilities,
                'evolutions' => $processedEvolutions,
                'types' => $types,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pokémon criado com sucesso!',
                'redirect_url' => route('custom-pokemons.show', $customPokemon)
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Erro ao criar Pokémon: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar Pokémon: ' . $e->getMessage()
            ], 500);
        }
    }

    // Método auxiliar para processar imagem
    private function processImage($request)
    {
        // Caso 1: Upload de arquivo
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'pokemons/' . Str::random(40) . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->put($filename, file_get_contents($file));
            
            // Copiar para public/storage
            $publicPath = base_path('public/storage/' . $filename);
            $publicDir = dirname($publicPath);
            if (!is_dir($publicDir)) {
                mkdir($publicDir, 0755, true);
            }
            copy(storage_path('app/public/' . $filename), $publicPath);
            
            return 'storage/' . $filename;
        }
        // Caso 2: URL Base64
        elseif ($request->filled('image_url') && str_starts_with($request->image_url, 'data:image')) {
            return $this->saveBase64Image($request->image_url);
        }
        // Caso 3: URL externa
        elseif ($request->filled('image_url') && filter_var($request->image_url, FILTER_VALIDATE_URL)) {
            return $request->image_url;
        }
        
        return null;
    }

    // Método auxiliar para salvar imagem Base64
    private function saveBase64Image($base64Image)
    {
        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
        $imageData = str_replace(' ', '+', $imageData);
        $imageBinary = base64_decode($imageData);
        
        if ($imageBinary !== false) {
            $filename = 'pokemons/' . Str::random(40) . '.png';
            Storage::disk('public')->put($filename, $imageBinary);
            
            // Copiar para public/storage
            $publicPath = base_path('public/storage/' . $filename);
            $publicDir = dirname($publicPath);
            if (!is_dir($publicDir)) {
                mkdir($publicDir, 0755, true);
            }
            copy(storage_path('app/public/' . $filename), $publicPath);
            
            return 'storage/' . $filename;
        }
        
        return null;
    }

    public function generateImage(Request $request)
    {
        try {
            $request->validate([
                'prompt' => 'required|string|min:3|max:500',
            ]);

            $prompt = "pokemon, " . $request->prompt . ", official pokemon art style, vibrant colors, detailed, high quality, 4k";

            $imageUrl = "https://image.pollinations.ai/prompt/" . urlencode($prompt);
            
            $response = Http::timeout(60)->get($imageUrl);
            
            if ($response->successful()) {
                $filename = 'pokemons/' . Str::random(40) . '.png';
                $saved = Storage::disk('public')->put($filename, $response->body());
                
                if ($saved) {
                    $localUrl = asset('storage/' . $filename);
                    return response()->json([
                        'success' => true,
                        'image_url' => $localUrl,
                        'message' => 'Imagem gerada com sucesso!'
                    ]);
                } else {
                    throw new \Exception('Não foi possível salvar a imagem');
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Falha ao gerar imagem. Tente novamente.'
                ], 500);
            }
            
        } catch (\Exception $e) {
            Log::error('Erro ao gerar imagem: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(CustomPokemon $customPokemon)
    {
        // Processar imagem principal
        $imageUrl = $customPokemon->image_url;
        if ($imageUrl && !str_starts_with($imageUrl, 'http')) {
            $imageUrl = asset($imageUrl);
        }
        
        $pokemon = [
            'id' => 'custom_' . $customPokemon->id,
            'is_custom' => true,
            'name' => $customPokemon->name,
            'height' => $customPokemon->height ?? 17,
            'weight' => $customPokemon->weight ?? 905,
            'base_experience' => $customPokemon->base_experience ?? 100,
            'stats' => $customPokemon->stats ?? [],
            'abilities' => $customPokemon->abilities ?? [],
            'image_url' => $imageUrl,
            'sprites' => [
                'other' => [
                    'official-artwork' => [
                        'front_default' => $imageUrl ?? 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/0.png'
                    ]
                ]
            ]
        ];

        // Processar evoluções com suas imagens
        $evolucaoDetalhes = [];
        foreach ($customPokemon->evolutions ?? [] as $evo) {
            $evoImage = $evo['image'] ?? null;
            
            // Se a imagem for caminho local, gerar URL completa
            if ($evoImage && !str_starts_with($evoImage, 'http')) {
                $evoImage = asset($evoImage);
            }
            
            // Se não tiver imagem, usar imagem padrão
            if (!$evoImage) {
                $evoImage = 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/0.png';
            }
            
            $evolucaoDetalhes[] = [
                'id' => 'custom_evo_' . md5($evo['name'] ?? ''),
                'nome' => $evo['name'] ?? 'Evolução',
                'foto' => $evoImage
            ];
        }

        return view('pokemon', compact('pokemon', 'evolucaoDetalhes'));
    }

public function destroy(CustomPokemon $customPokemon)
{
    try {
        // Deletar imagem principal se existir e for local
        if ($customPokemon->image_url) {
            // Remove a URL base para pegar o caminho do arquivo
            $path = $customPokemon->image_url;
            
            // Se for URL de storage
            if (str_contains($path, '/storage/')) {
                $path = str_replace(asset('storage/'), '', $path);
                $path = str_replace('/storage/', '', $path);
            }
            // Se for caminho relativo
            elseif (!str_starts_with($path, 'http')) {
                $path = str_replace('storage/', '', $path);
            }
            
            // Deletar arquivo se existir
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
        
        // Deletar imagens das evoluções se existirem
        $evolutions = $customPokemon->evolutions ?? [];
        foreach ($evolutions as $evolution) {
            if (isset($evolution['image']) && $evolution['image']) {
                $evoPath = $evolution['image'];
                if (str_contains($evoPath, '/storage/')) {
                    $evoPath = str_replace(asset('storage/'), '', $evoPath);
                    $evoPath = str_replace('/storage/', '', $evoPath);
                } elseif (!str_starts_with($evoPath, 'http')) {
                    $evoPath = str_replace('storage/', '', $evoPath);
                }
                
                if ($evoPath && Storage::disk('public')->exists($evoPath)) {
                    Storage::disk('public')->delete($evoPath);
                }
            }
        }
        
        $customPokemon->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Pokémon excluído com sucesso!',
            'redirect_url' => route('pokemon.show', 'pikachu') // Redireciona para a página principal
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erro ao excluir Pokémon: ' . $e->getMessage()
        ], 500);
    }
}
    
    public function edit(CustomPokemon $customPokemon)
    {
        // Implementar edição se necessário
        return view('custom-pokemon.edit', compact('customPokemon'));
    }
    
public function update(Request $request, CustomPokemon $customPokemon)
{
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'base_experience' => 'nullable|integer',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,gif|max:20480',
            'image_url' => 'nullable|string',
            'stats' => 'nullable|string',
            'abilities' => 'nullable|string',
            'evolutions' => 'nullable|string',
            'types' => 'nullable|string',
        ]);

        $stats = is_string($request->stats) ? json_decode($request->stats, true) : ($request->stats ?? []);
        $abilities = is_string($request->abilities) ? json_decode($request->abilities, true) : ($request->abilities ?? []);
        $evolutions = is_string($request->evolutions) ? json_decode($request->evolutions, true) : ($request->evolutions ?? []);
        $types = is_string($request->types) ? json_decode($request->types, true) : ($request->types ?? []);

        // Processar a imagem principal (se nova)
        if ($request->hasFile('image')) {
            // Deletar imagem antiga
            if ($customPokemon->image_url) {
                $oldPath = str_replace(asset('storage/'), '', $customPokemon->image_url);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $imageUrl = $this->processImage($request);
        } 
        elseif ($request->filled('image_url') && str_starts_with($request->image_url, 'data:image')) {
            // Imagem Base64 nova
            if ($customPokemon->image_url) {
                $oldPath = str_replace(asset('storage/'), '', $customPokemon->image_url);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $imageUrl = $this->saveBase64Image($request->image_url);
        }
        elseif ($request->filled('image_url') && filter_var($request->image_url, FILTER_VALIDATE_URL)) {
            // URL externa
            $imageUrl = $request->image_url;
        }
        else {
            // Manter imagem existente
            $imageUrl = $customPokemon->image_url;
        }
        
        // Processar as imagens das evoluções
        $processedEvolutions = [];
        foreach ($evolutions as $evolution) {
            $evolutionImage = $evolution['image'] ?? null;
            
            // Se a imagem da evolução for Base64 (upload via preview)
            if ($evolutionImage && str_starts_with($evolutionImage, 'data:image')) {
                // Deletar imagem antiga da evolução se existir e for local
                // (opcional)
                $evolutionImage = $this->saveBase64Image($evolutionImage);
            }
            
            $processedEvolutions[] = [
                'name' => $evolution['name'],
                'image' => $evolutionImage
            ];
        }

        // Formatar stats
        $formattedStats = [];
        $statMappings = [
            'hp' => 'hp',
            'attack' => 'attack',
            'defense' => 'defense',
            'special-attack' => 'special-attack',
            'special-defense' => 'special-defense',
            'speed' => 'speed'
        ];

        foreach ($statMappings as $key => $statName) {
            $value = $stats[$key] ?? 50;
            $formattedStats[] = [
                'stat' => ['name' => $statName],
                'base_stat' => (int) $value
            ];
        }

        // Formatar habilidades
        $formattedAbilities = [];
        if (!empty($abilities)) {
            foreach ($abilities as $ability) {
                if (!empty($ability['name'])) {
                    $formattedAbilities[] = [
                        'ability' => ['name' => $ability['name']],
                        'is_hidden' => isset($ability['is_hidden']) && $ability['is_hidden']
                    ];
                }
            }
        }

        // Atualizar Pokémon
        $customPokemon->update([
            'name' => $validated['name'],
            'height' => (int)($validated['height'] ?? 17),
            'weight' => (int)($validated['weight'] ?? 905),
            'base_experience' => (int)($validated['base_experience'] ?? 100),
            'image_url' => $imageUrl,
            'stats' => $formattedStats,
            'abilities' => $formattedAbilities,
            'evolutions' => $processedEvolutions,
            'types' => $types,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pokémon atualizado com sucesso!',
            'redirect_url' => route('custom-pokemons.show', $customPokemon)
        ], 200);

    } catch (\Exception $e) {
        \Log::error('Erro ao atualizar Pokémon: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Erro ao atualizar Pokémon: ' . $e->getMessage()
        ], 500);
    }
}

public function search(Request $request)
{
    $name = $request->get('name');
    
    // Busca exata primeiro
    $pokemon = CustomPokemon::where('name', 'like', $name)->first();
    
    // Se não encontrar, busca com like
    if (!$pokemon) {
        $pokemon = CustomPokemon::where('name', 'like', "%{$name}%")->first();
    }
    
    if ($pokemon) {
        return response()->json([
            'found' => true,
            'id' => $pokemon->id,
            'name' => $pokemon->name
        ]);
    }
    
    return response()->json([
        'found' => false
    ]);
}

    // Método para API retornar todos os customizados em JSON
// Método para API retornar todos os customizados em JSON
// No CustomPokemonController.php, substitua o método indexJson:

public function indexJson()
{
    try {
        // Buscar todos os Pokémon customizados SEM ordenação para evitar erro de memória
        $pokemons = CustomPokemon::all(); // Remove o orderBy
        
        // Se não houver nenhum, retorna array vazio
        if ($pokemons->isEmpty()) {
            return response()->json([]);
        }
        
        $formattedPokemons = [];
        
        foreach ($pokemons as $p) {
            // Processar imagem URL
            $imageUrl = $p->image_url;
            
            // Se a imagem for local (storage ou public)
            if ($imageUrl) {
                if (str_starts_with($imageUrl, 'storage/')) {
                    $imageUrl = asset($imageUrl);
                } elseif (!str_starts_with($imageUrl, 'http')) {
                    $imageUrl = asset('storage/' . $imageUrl);
                }
            }
            
            // Se não tiver imagem, usa a padrão
            if (!$imageUrl) {
                $imageUrl = 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/0.png';
            }
            
            // Processar tipos
            $types = $p->types;
            if (is_string($types)) {
                $types = json_decode($types, true);
            }
            if (!is_array($types)) {
                $types = [];
            }
            
            $formattedPokemons[] = [
                'id' => $p->id,
                'name' => $p->name,
                'image_url' => $imageUrl,
                'types' => $types,
                'is_custom' => true,
                'created_at' => $p->created_at ? $p->created_at->toISOString() : null
            ];
        }
        
        // Ordenar em PHP em vez do MySQL (evita erro de memória)
        usort($formattedPokemons, function($a, $b) {
            return strtotime($b['created_at'] ?? 'now') - strtotime($a['created_at'] ?? 'now');
        });
        
        return response()->json($formattedPokemons);
        
    } catch (\Exception $e) {
        \Log::error('Erro no indexJson: ' . $e->getMessage());
        return response()->json([
            'error' => 'Erro ao carregar Pokémon customizados',
            'message' => $e->getMessage()
        ], 500);
    }
}


}