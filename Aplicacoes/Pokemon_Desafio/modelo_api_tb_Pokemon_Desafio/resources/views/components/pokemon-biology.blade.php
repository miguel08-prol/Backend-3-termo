{{-- resources/views/components/pokemon-biology.blade.php --}}
@php
    use App\Helpers\TypeHelper;
    
    // Debug - remover depois
    // Log::info('Pokemon data:', ['pokemon' => $pokemon]);
    
    // Pegar os tipos de forma segura
    $pokemonTypes = [];
    
    if (isset($pokemon['types'])) {
        $typesRaw = $pokemon['types'];
        
        // Se for string, decodificar JSON
        if (is_string($typesRaw)) {
            $typesRaw = json_decode($typesRaw, true);
        }
        
        // Se for array, extrair os nomes dos tipos
        if (is_array($typesRaw)) {
            foreach ($typesRaw as $type) {
                if (is_string($type)) {
                    $pokemonTypes[] = strtolower($type);
                } elseif (is_array($type) && isset($type['name'])) {
                    $pokemonTypes[] = strtolower($type['name']);
                } elseif (is_array($type) && isset($type['type']['name'])) {
                    $pokemonTypes[] = strtolower($type['type']['name']);
                }
            }
        }
    }
    
    // Se não achou tipos, tentar pegar do array direto
    if (empty($pokemonTypes) && isset($pokemon['types_formatted'])) {
        $pokemonTypes = $pokemon['types_formatted'];
    }
    
    // Calcular fraquezas e resistências
    $weaknesses = [];
    $resistances = [];
    $immunities = [];
    $strongAgainst = [];
    
    if (!empty($pokemonTypes)) {
        $typeData = TypeHelper::getWeaknessesAndResistances($pokemonTypes);
        $weaknesses = $typeData['weaknesses'] ?? [];
        $resistances = $typeData['resistances'] ?? [];
        $immunities = $typeData['immunities'] ?? [];
        $strongAgainst = TypeHelper::getStrongAgainst($pokemonTypes);
    }
    
    // Descrição
    $description = $pokemon['description'] ?? '';
    if (empty($description)) {
        if (!empty($pokemonTypes)) {
            $typeNames = array_map(function($type) {
                return TypeHelper::getTypeNamePortuguese($type);
            }, $pokemonTypes);
            $description = ucfirst($pokemon['name']) . " é um Pokémon do tipo " . implode('/', $typeNames) . ". ";
        } else {
            $description = ucfirst($pokemon['name']) . " é um Pokémon incrível! ";
        }
        $description .= "Com suas habilidades únicas, este Pokémon é uma adição valiosa a qualquer equipe de treinadores.";
    }
    
    // Altura, peso e experiência
    $height = isset($pokemon['height']) ? $pokemon['height'] : 0;
    $weight = isset($pokemon['weight']) ? $pokemon['weight'] : 0;
    $baseExp = isset($pokemon['base_experience']) ? $pokemon['base_experience'] : 0;
    $isCustom = isset($pokemon['is_custom']) && $pokemon['is_custom'] === true;
    
    // Calcular total de stats
    $totalStats = 0;
    if (isset($pokemon['stats']) && is_array($pokemon['stats'])) {
        foreach ($pokemon['stats'] as $stat) {
            if (is_array($stat) && isset($stat['base_stat'])) {
                $totalStats += $stat['base_stat'];
            } elseif (is_array($stat) && isset($stat['stat']['base_stat'])) {
                $totalStats += $stat['stat']['base_stat'];
            }
        }
    }
    
    // Determinar nível de poder
    $powerLevel = '';
    $powerColor = '';
    if ($totalStats >= 600) {
        $powerLevel = 'Lendário';
        $powerColor = 'text-red-400';
    } elseif ($totalStats >= 500) {
        $powerLevel = 'Épico';
        $powerColor = 'text-purple-400';
    } elseif ($totalStats >= 400) {
        $powerLevel = 'Avançado';
        $powerColor = 'text-blue-400';
    } elseif ($totalStats >= 300) {
        $powerLevel = 'Intermediário';
        $powerColor = 'text-green-400';
    } elseif ($totalStats > 0) {
        $powerLevel = 'Iniciante';
        $powerColor = 'text-gray-400';
    }
@endphp

<div class="space-y-6">
    <!-- DESCRIÇÃO -->
    <div class="glass-card p-6 rounded-2xl">
        <div class="flex items-center gap-3 mb-4">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h3 class="text-xl font-bold">📖 Sobre {{ ucfirst($pokemon['name']) }}</h3>
            @if($isCustom)
                <span class="ml-2 text-xs bg-purple-500/20 text-purple-400 px-2 py-1 rounded-full">⭐ Customizado</span>
            @endif
        </div>
        <p class="text-gray-300 leading-relaxed">{{ $description }}</p>
    </div>

    <!-- TIPOS -->
    @if(!empty($pokemonTypes))
    <div class="glass-card p-6 rounded-2xl">
        <div class="flex items-center gap-3 mb-4">
            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
            </svg>
            <h3 class="text-xl font-bold">🔮 Tipos</h3>
        </div>
        <div class="flex flex-wrap gap-3">
            @foreach($pokemonTypes as $type)
            @php
                $typeColor = TypeHelper::getTypeColor($type);
                $typeName = TypeHelper::getTypeNamePortuguese($type);
                $isDarkText = in_array($type, ['electric', 'ice', 'normal', 'steel', 'ground', 'fairy']);
            @endphp
            <span class="px-5 py-2 rounded-full text-sm font-bold shadow-lg" 
                  style="background: {{ $typeColor }}; color: {{ $isDarkText ? '#000' : '#fff' }}">
                {{ $typeName }}
            </span>
            @endforeach
        </div>
    </div>

    <!-- FORTE CONTRA, FRAQUEZAS E RESISTÊNCIAS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Forte Contra -->
        <div class="glass-card p-6 rounded-2xl">
            <div class="flex items-center gap-3 mb-4">
                <h3 class="text-lg font-bold">⚡ Forte Contra</h3>
            </div>
            @if(!empty($strongAgainst))
                <div class="flex flex-wrap gap-2">
                    @foreach($strongAgainst as $strongType)
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-500/20 text-green-400 border border-green-500/30">
                            {{ $strongType }}
                        </span>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-sm">Nenhuma vantagem significativa</p>
            @endif
        </div>

        <!-- Fraquezas -->
        <div class="glass-card p-6 rounded-2xl">
            <div class="flex items-center gap-3 mb-4">
                <h3 class="text-lg font-bold">⚠️ Fraquezas</h3>
            </div>
            @if(!empty($weaknesses))
                <div class="space-y-2">
                    @foreach($weaknesses as $weakness)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">{{ $weakness['name'] }}</span>
                            <span class="text-red-400 font-bold">{{ $weakness['multiplier'] }}x</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-sm">Nenhuma fraqueza significativa!</p>
            @endif
        </div>

        <!-- Resistências -->
        <div class="glass-card p-6 rounded-2xl">
            <div class="flex items-center gap-3 mb-4">
                <h3 class="text-lg font-bold">🛡️ Resistências</h3>
            </div>
            @if(!empty($resistances))
                <div class="space-y-2">
                    @foreach($resistances as $resistance)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">{{ $resistance['name'] }}</span>
                            <span class="text-blue-400 font-bold">{{ $resistance['multiplier'] }}x</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-sm">Nenhuma resistência especial</p>
            @endif
        </div>
    </div>

    <!-- Imunidades -->
    @if(!empty($immunities))
    <div class="glass-card p-6 rounded-2xl">
        <div class="flex items-center gap-3 mb-4">
            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-xl font-bold">✨ Imunidades</h3>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach($immunities as $immunity)
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">
                    {{ $immunity }}
                </span>
            @endforeach
        </div>
    </div>
    @endif
    @else
    <!-- Sem tipos definidos -->
    <div class="glass-card p-6 rounded-2xl text-center">
        <svg class="w-12 h-12 mx-auto mb-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-gray-400">Nenhum tipo definido para este Pokémon</p>
        @if($isCustom)
            <p class="text-sm text-gray-500 mt-2">Você pode editar o Pokémon para adicionar tipos</p>
        @endif
    </div>
    @endif

    <!-- INFORMAÇÕES GERAIS -->
    <div class="glass-card p-6 rounded-2xl">
        <div class="flex items-center gap-3 mb-4">
            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <h3 class="text-xl font-bold">📊 Informações Gerais</h3>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center p-3 rounded-xl bg-white/5">
                <p class="text-gray-400 text-xs uppercase tracking-wider">Altura</p>
                <p class="text-2xl font-bold">{{ number_format($height / 10, 1) }} m</p>
            </div>
            <div class="text-center p-3 rounded-xl bg-white/5">
                <p class="text-gray-400 text-xs uppercase tracking-wider">Peso</p>
                <p class="text-2xl font-bold">{{ number_format($weight / 10, 1) }} kg</p>
            </div>
            <div class="text-center p-3 rounded-xl bg-white/5">
                <p class="text-gray-400 text-xs uppercase tracking-wider">Experiência</p>
                <p class="text-2xl font-bold">{{ $baseExp }}</p>
            </div>
            <div class="text-center p-3 rounded-xl bg-white/5">
                <p class="text-gray-400 text-xs uppercase tracking-wider">Status Total</p>
                <p class="text-2xl font-bold">{{ $totalStats }}</p>
            </div>
        </div>
        
        <!-- Barra de poder -->
        @if($totalStats > 0 && !empty($powerLevel))
        <div class="mt-4 pt-4 border-t border-white/10">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm text-gray-400">Nível de Poder</span>
                <span class="text-sm font-bold {{ $powerColor }}">{{ $powerLevel }}</span>
            </div>
            <div class="w-full bg-white/10 rounded-full h-2 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 h-full rounded-full transition-all duration-1000" 
                     style="width: {{ min(100, ($totalStats / 780) * 100) }}%"></div>
            </div>
            <p class="text-xs text-gray-500 mt-2 text-center">{{ $totalStats }} / 780 (máximo teórico)</p>
        </div>
        @endif
    </div>
</div>

<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .glass-card:hover {
        transform: translateY(-2px);
        border-color: rgba(59, 130, 246, 0.3);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }
</style>