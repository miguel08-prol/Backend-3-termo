<?php

namespace App\Http\Controllers;

use App\Models\CustomPokemon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Helpers\ImageHelper;

class CustomPokemonController extends Controller
{
    /**
     * Lista todos os Pokémon customizados
     */
    public function index(Request $request)
    {
        try {
            if ($request->wantsJson() || $request->get('format') === 'json') {
                return $this->indexJson();
            }
            
            $pokemons = CustomPokemon::all()->sortByDesc('created_at');
            return view('custom-pokemon.index', compact('pokemons'));
        } catch (\Exception $e) {
            Log::error('Erro no index: ' . $e->getMessage());
            
            if ($request->wantsJson() || $request->get('format') === 'json') {
                return response()->json(['error' => 'Erro ao carregar Pokémon'], 500);
            }
            
            return view('custom-pokemon.index', ['pokemons' => collect([]), 'error' => $e->getMessage()]);
        }
    }

    /**
     * Retorna lista em JSON para a API
     */
    public function indexJson()
    {
        try {
            $pokemons = CustomPokemon::all();
            
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
                
                if (!$imageUrl) {
                    $imageUrl = 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/0.png';
                }
                
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
            
            // Ordenar por data decrescente
            usort($formattedPokemons, function($a, $b) {
                return strtotime($b['created_at'] ?? 'now') - strtotime($a['created_at'] ?? 'now');
            });
            
            return response()->json($formattedPokemons);
        } catch (\Exception $e) {
            Log::error('Erro no indexJson: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erro ao carregar Pokémon customizados',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostra formulário de criação
     */
    public function create()
    {
        return view('custom-pokemon.create');
    }

    /**
     * Salva um novo Pokémon customizado
     */
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
                'description' => 'nullable|string',
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
                
                if ($evolutionImage && str_starts_with($evolutionImage, 'data:image')) {
                    $evolutionImage = ImageHelper::saveBase64Image($evolutionImage, 'pokemons');
                    if ($evolutionImage) {
                        $evolutionImage = route('image.serve', ['path' => $evolutionImage]);
                    }
                }
                
                $processedEvolutions[] = [
                    'name' => $evolution['name'],
                    'image' => $evolutionImage
                ];
            }

            // Formatar stats
            $formattedStats = $this->formatStats($stats);
            
            // Formatar habilidades
            $formattedAbilities = $this->formatAbilities($abilities);

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
                'description' => $request->input('description', ''),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pokémon criado com sucesso!',
                'redirect_url' => route('custom-pokemons.show', $customPokemon)
            ], 201);

        } catch (\Exception $e) {
            Log::error('Erro ao criar Pokémon: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar Pokémon: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostra um Pokémon customizado
     */
    public function show(CustomPokemon $customPokemon)
    {
        $imageUrl = $this->getImageUrl($customPokemon->image_url);
        
        $types = $customPokemon->types;
        if (is_string($types)) {
            $types = json_decode($types, true);
        }
        if (!is_array($types)) {
            $types = [];
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
            'description' => $customPokemon->description ?? '',
            'types' => $types,
            'sprites' => [
                'other' => [
                    'official-artwork' => [
                        'front_default' => $imageUrl
                    ]
                ]
            ]
        ];

        // Processar evoluções
        $evolucaoDetalhes = [];
        foreach ($customPokemon->evolutions ?? [] as $evo) {
            $evoImage = $this->getImageUrl($evo['image'] ?? null);
            
            $evolucaoDetalhes[] = [
                'id' => 'custom_evo_' . md5($evo['name'] ?? ''),
                'nome' => $evo['name'] ?? 'Evolução',
                'foto' => $evoImage
            ];
        }

        return view('pokemon', compact('pokemon', 'evolucaoDetalhes'));
    }

    /**
     * Mostra formulário de edição
     */
    public function edit(CustomPokemon $customPokemon)
    {
        return view('custom-pokemon.edit', compact('customPokemon'));
    }

    /**
     * Atualiza um Pokémon customizado
     */
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
                'description' => 'nullable|string',
            ]);

            $stats = is_string($request->stats) ? json_decode($request->stats, true) : ($request->stats ?? []);
            $abilities = is_string($request->abilities) ? json_decode($request->abilities, true) : ($request->abilities ?? []);
            $evolutions = is_string($request->evolutions) ? json_decode($request->evolutions, true) : ($request->evolutions ?? []);
            $types = is_string($request->types) ? json_decode($request->types, true) : ($request->types ?? []);

            // Processar a imagem principal
            $imageUrl = $this->processImageUpdate($request, $customPokemon);
            
            // Processar as imagens das evoluções
            $processedEvolutions = [];
            foreach ($evolutions as $evolution) {
                $evolutionImage = $evolution['image'] ?? null;
                
                if ($evolutionImage && str_starts_with($evolutionImage, 'data:image')) {
                    $evolutionImage = ImageHelper::saveBase64Image($evolutionImage, 'pokemons');
                    if ($evolutionImage) {
                        $evolutionImage = route('image.serve', ['path' => $evolutionImage]);
                    }
                }
                
                $processedEvolutions[] = [
                    'name' => $evolution['name'],
                    'image' => $evolutionImage
                ];
            }

            // Formatar stats
            $formattedStats = $this->formatStats($stats);
            
            // Formatar habilidades
            $formattedAbilities = $this->formatAbilities($abilities);

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
                'description' => $request->input('description', ''),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pokémon atualizado com sucesso!',
                'redirect_url' => route('custom-pokemons.show', $customPokemon)
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao atualizar Pokémon: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar Pokémon: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove um Pokémon customizado
     */
    public function destroy(CustomPokemon $customPokemon)
{
    try {
        // Deletar imagem principal se existir e for local
        if ($customPokemon->image_url) {
            $this->deleteImageFile($customPokemon->image_url);
        }
        
        // Deletar imagens das evoluções
        $evolutions = $customPokemon->evolutions ?? [];
        foreach ($evolutions as $evolution) {
            if (isset($evolution['image']) && $evolution['image']) {
                $this->deleteImageFile($evolution['image']);
            }
        }
        
        $customPokemon->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Pokémon excluído com sucesso!',
            'redirect_url' => route('pokemon.show', 'pikachu')
        ]);
    } catch (\Exception $e) {
        \Log::error('Erro ao excluir Pokémon: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Erro ao excluir Pokémon: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Busca Pokémon por nome
     */
    public function search(Request $request)
{
    $name = $request->get('name');
    
    // Busca exata primeiro (mais precisa)
    $pokemon = CustomPokemon::where('name', $name)->first();
    
    // Se não encontrar, busca por similaridade
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
    
    return response()->json(['found' => false]);
}

    /**
     * Gera imagem usando o método principal (tenta Python primeiro)
     */
    public function generateImage(Request $request)
    {
        $pythonResponse = $this->generateImageWithPython($request);
        $data = json_decode($pythonResponse->getContent(), true);
        
        if ($data['success'] ?? false) {
            return $pythonResponse;
        }
        
        return $this->generateImageFallback($request);
    }

    /**
     * Gera imagem usando Python
     */
    public function generateImageWithPython(Request $request)
    {
        try {
            $request->validate([
                'prompt' => 'required|string|min:3|max:500',
            ]);

            $prompt = $request->prompt;
            $pythonScript = base_path('back2/generate_pokemon.py');
            
            if (!file_exists($pythonScript)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Script Python não encontrado'
                ]);
            }
            
            $command = 'python ' . escapeshellarg($pythonScript) . ' ' . escapeshellarg($prompt);
            $output = shell_exec($command);
            $result = json_decode($output, true);
            
            if ($result && isset($result['success']) && $result['success'] && isset($result['image_base64'])) {
                $imageData = base64_decode($result['image_base64']);
                $filename = ImageHelper::saveImage($imageData, 'pokemons');
                $imageUrl = route('image.serve', ['path' => $filename]);
                
                return response()->json([
                    'success' => true,
                    'image_url' => $imageUrl,
                    'message' => 'Imagem gerada com sucesso!'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Erro ao gerar imagem'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Fallback para geração de imagem
     */
    public function generateImageFallback(Request $request)
    {
        try {
            $request->validate([
                'prompt' => 'required|string|min:3|max:500',
            ]);

            $prompt = $request->prompt;
            $encodedPrompt = urlencode("pokemon " . $prompt . ", official art style, white background");
            $imageUrl = "https://image.pollinations.ai/prompt/{$encodedPrompt}?width=512&height=512";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $imageUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $imageContent = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode === 200 && $imageContent) {
                $filename = ImageHelper::saveImage($imageContent, 'pokemons');
                $localUrl = route('image.serve', ['path' => $filename]);
                
                return response()->json([
                    'success' => true,
                    'image_url' => $localUrl,
                    'message' => 'Imagem gerada com sucesso!'
                ]);
            }
            
            $defaultImage = 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/25.png';
            
            return response()->json([
                'success' => true,
                'image_url' => $defaultImage,
                'message' => 'Usando imagem padrão'
            ]);
        } catch (\Exception $e) {
            Log::error('Erro no fallback: ' . $e->getMessage());
            
            return response()->json([
                'success' => true,
                'image_url' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/25.png',
                'message' => 'Usando imagem padrão'
            ]);
        }
    }

    // ==================== MÉTODOS PRIVADOS AUXILIARES ====================

    /**
     * Processa a imagem para criação
     */
    private function processImage($request)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageContent = file_get_contents($file);
            $filename = ImageHelper::saveImage($imageContent, 'pokemons');
            return route('image.serve', ['path' => $filename]);
        } elseif ($request->filled('image_url') && str_starts_with($request->image_url, 'data:image')) {
            $filename = ImageHelper::saveBase64Image($request->image_url, 'pokemons');
            return route('image.serve', ['path' => $filename]);
        } elseif ($request->filled('image_url') && filter_var($request->image_url, FILTER_VALIDATE_URL)) {
            return $request->image_url;
        }
        
        return null;
    }

    /**
     * Processa a imagem para atualização
     */
    private function processImageUpdate($request, $customPokemon)
    {
        if ($request->hasFile('image')) {
            if ($customPokemon->image_url) {
                $this->deleteImageFile($customPokemon->image_url);
            }
            $file = $request->file('image');
            $imageContent = file_get_contents($file);
            $filename = ImageHelper::saveImage($imageContent, 'pokemons');
            return route('image.serve', ['path' => $filename]);
        } elseif ($request->filled('image_url') && str_starts_with($request->image_url, 'data:image')) {
            if ($customPokemon->image_url) {
                $this->deleteImageFile($customPokemon->image_url);
            }
            $filename = ImageHelper::saveBase64Image($request->image_url, 'pokemons');
            return route('image.serve', ['path' => $filename]);
        } elseif ($request->filled('image_url') && filter_var($request->image_url, FILTER_VALIDATE_URL)) {
            return $request->image_url;
        }
        
        return $customPokemon->image_url;
    }

    /**
     * Formata stats para o padrão do sistema
     */
    private function formatStats($stats)
    {
        $statMappings = [
            'hp' => 'hp',
            'attack' => 'attack',
            'defense' => 'defense',
            'special-attack' => 'special-attack',
            'special-defense' => 'special-defense',
            'speed' => 'speed'
        ];

        $formattedStats = [];
        foreach ($statMappings as $key => $statName) {
            $value = $stats[$key] ?? 50;
            $formattedStats[] = [
                'stat' => ['name' => $statName],
                'base_stat' => (int) $value
            ];
        }
        
        return $formattedStats;
    }

    /**
     * Formata habilidades para o padrão do sistema
     */
    private function formatAbilities($abilities)
    {
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
        return $formattedAbilities;
    }

    /**
     * Obtém URL da imagem
     */
    private function getImageUrl($imageUrl)
    {
        if (!$imageUrl) {
            return 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/0.png';
        }
        
        if (str_starts_with($imageUrl, 'storage/')) {
            $path = str_replace('storage/', '', $imageUrl);
            return route('image.serve', ['path' => $path]);
        } elseif (str_starts_with($imageUrl, 'pokemons/')) {
            return route('image.serve', ['path' => $imageUrl]);
        } elseif (!str_starts_with($imageUrl, 'http')) {
            return route('image.serve', ['path' => $imageUrl]);
        }
        
        return $imageUrl;
    }

    /**
     * Remove arquivo de imagem do storage
     */
    private function deleteImageFile($imageUrl)
{
    if (empty($imageUrl)) {
        return;
    }
    
    $path = null;
    
    // Caso 1: URL da rota image.serve (ex: /image/pokemons/arquivo.png)
    if (preg_match('/\/image\/(.+)$/', $imageUrl, $matches)) {
        $path = $matches[1];
    }
    // Caso 2: URL do storage (ex: /storage/pokemons/arquivo.png)
    elseif (str_contains($imageUrl, '/storage/')) {
        $path = str_replace('/storage/', '', parse_url($imageUrl, PHP_URL_PATH));
        $path = ltrim($path, '/');
    }
    // Caso 3: Apenas o caminho (ex: pokemons/arquivo.png)
    elseif (!str_starts_with($imageUrl, 'http') && !str_starts_with($imageUrl, 'data:')) {
        $path = $imageUrl;
    }
    // Caso 4: URL completa com asset
    elseif (str_contains($imageUrl, asset('storage/'))) {
        $path = str_replace(asset('storage/'), '', $imageUrl);
    }
    
    // Se encontrou um caminho válido, deletar
    if ($path && !str_starts_with($path, 'http') && !str_starts_with($path, 'data:')) {
        // Limpar o caminho
        $path = preg_replace('/\?.*$/', '', $path); // Remove query strings
        $path = ltrim($path, '/');
        
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            \Log::info('Imagem deletada: ' . $path);
        } else {
            \Log::warning('Imagem não encontrada para deletar: ' . $path);
        }
    }
}

/**
 * Busca Pokémon customizado por nome exato (para evolução)
 */
public function getByName($name)
{
    $pokemon = CustomPokemon::where('name', 'like', $name)->first();
    
    if (!$pokemon) {
        $pokemon = CustomPokemon::where('name', 'like', "%{$name}%")->first();
    }
    
    if ($pokemon) {
        $imageUrl = $pokemon->image_url;
        if ($imageUrl && !str_starts_with($imageUrl, 'http')) {
            $imageUrl = asset('storage/' . $imageUrl);
        }
        
        $types = $pokemon->types;
        if (is_string($types)) $types = json_decode($types, true);
        
        $stats = $pokemon->stats;
        if (is_string($stats)) $stats = json_decode($stats, true);
        
        return response()->json([
            'id' => 'custom_' . $pokemon->id,
            'name' => $pokemon->name,
            'height' => $pokemon->height ?? 17,
            'weight' => $pokemon->weight ?? 905,
            'base_experience' => $pokemon->base_experience ?? 100,
            'stats' => $stats ?? [],
            'types' => $types ?? [],
            'image_url' => $imageUrl,
            'is_custom' => true,
            'evolutions' => $pokemon->evolutions ?? []
        ]);
    }
    
    return response()->json(['error' => 'Não encontrado'], 404);
}
}