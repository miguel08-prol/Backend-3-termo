<?php
// app/Helpers/TypeHelper.php

namespace App\Helpers;

class TypeHelper
{
    // Matriz de efetividade dos tipos (ataque vs defesa)
    // Valores: 0 = imune, 0.5 = não muito efetivo, 1 = normal, 2 = super efetivo
    public static $typeChart = [
        'normal' => [
            'normal' => 1, 'fire' => 1, 'water' => 1, 'electric' => 1, 'grass' => 1,
            'ice' => 1, 'fighting' => 1, 'poison' => 1, 'ground' => 1, 'flying' => 1,
            'psychic' => 1, 'bug' => 1, 'rock' => 0.5, 'ghost' => 0, 'dragon' => 1,
            'dark' => 1, 'steel' => 0.5, 'fairy' => 1
        ],
        'fire' => [
            'normal' => 1, 'fire' => 0.5, 'water' => 0.5, 'electric' => 1, 'grass' => 2,
            'ice' => 2, 'fighting' => 1, 'poison' => 1, 'ground' => 1, 'flying' => 1,
            'psychic' => 1, 'bug' => 2, 'rock' => 0.5, 'ghost' => 1, 'dragon' => 0.5,
            'dark' => 1, 'steel' => 2, 'fairy' => 1
        ],
        'water' => [
            'normal' => 1, 'fire' => 2, 'water' => 0.5, 'electric' => 1, 'grass' => 0.5,
            'ice' => 1, 'fighting' => 1, 'poison' => 1, 'ground' => 2, 'flying' => 1,
            'psychic' => 1, 'bug' => 1, 'rock' => 2, 'ghost' => 1, 'dragon' => 0.5,
            'dark' => 1, 'steel' => 1, 'fairy' => 1
        ],
        'electric' => [
            'normal' => 1, 'fire' => 1, 'water' => 2, 'electric' => 0.5, 'grass' => 0.5,
            'ice' => 1, 'fighting' => 1, 'poison' => 1, 'ground' => 0, 'flying' => 2,
            'psychic' => 1, 'bug' => 1, 'rock' => 1, 'ghost' => 1, 'dragon' => 0.5,
            'dark' => 1, 'steel' => 1, 'fairy' => 1
        ],
        'grass' => [
            'normal' => 1, 'fire' => 0.5, 'water' => 2, 'electric' => 1, 'grass' => 0.5,
            'ice' => 1, 'fighting' => 1, 'poison' => 0.5, 'ground' => 2, 'flying' => 0.5,
            'psychic' => 1, 'bug' => 0.5, 'rock' => 2, 'ghost' => 1, 'dragon' => 0.5,
            'dark' => 1, 'steel' => 0.5, 'fairy' => 1
        ],
        'ice' => [
            'normal' => 1, 'fire' => 0.5, 'water' => 0.5, 'electric' => 1, 'grass' => 2,
            'ice' => 0.5, 'fighting' => 1, 'poison' => 1, 'ground' => 2, 'flying' => 2,
            'psychic' => 1, 'bug' => 1, 'rock' => 1, 'ghost' => 1, 'dragon' => 2,
            'dark' => 1, 'steel' => 0.5, 'fairy' => 1
        ],
        'fighting' => [
            'normal' => 2, 'fire' => 1, 'water' => 1, 'electric' => 1, 'grass' => 1,
            'ice' => 2, 'fighting' => 1, 'poison' => 0.5, 'ground' => 1, 'flying' => 0.5,
            'psychic' => 0.5, 'bug' => 0.5, 'rock' => 2, 'ghost' => 0, 'dragon' => 1,
            'dark' => 2, 'steel' => 2, 'fairy' => 0.5
        ],
        'poison' => [
            'normal' => 1, 'fire' => 1, 'water' => 1, 'electric' => 1, 'grass' => 2,
            'ice' => 1, 'fighting' => 1, 'poison' => 0.5, 'ground' => 0.5, 'flying' => 1,
            'psychic' => 1, 'bug' => 1, 'rock' => 0.5, 'ghost' => 0.5, 'dragon' => 1,
            'dark' => 1, 'steel' => 0, 'fairy' => 2
        ],
        'ground' => [
            'normal' => 1, 'fire' => 2, 'water' => 1, 'electric' => 2, 'grass' => 0.5,
            'ice' => 1, 'fighting' => 1, 'poison' => 0.5, 'ground' => 1, 'flying' => 0,
            'psychic' => 1, 'bug' => 0.5, 'rock' => 2, 'ghost' => 1, 'dragon' => 1,
            'dark' => 1, 'steel' => 2, 'fairy' => 1
        ],
        'flying' => [
            'normal' => 1, 'fire' => 1, 'water' => 1, 'electric' => 0.5, 'grass' => 2,
            'ice' => 1, 'fighting' => 2, 'poison' => 1, 'ground' => 1, 'flying' => 1,
            'psychic' => 1, 'bug' => 2, 'rock' => 0.5, 'ghost' => 1, 'dragon' => 1,
            'dark' => 1, 'steel' => 0.5, 'fairy' => 1
        ],
        'psychic' => [
            'normal' => 1, 'fire' => 1, 'water' => 1, 'electric' => 1, 'grass' => 1,
            'ice' => 1, 'fighting' => 2, 'poison' => 2, 'ground' => 1, 'flying' => 1,
            'psychic' => 0.5, 'bug' => 1, 'rock' => 1, 'ghost' => 1, 'dragon' => 1,
            'dark' => 0, 'steel' => 0.5, 'fairy' => 1
        ],
        'bug' => [
            'normal' => 1, 'fire' => 0.5, 'water' => 1, 'electric' => 1, 'grass' => 2,
            'ice' => 1, 'fighting' => 0.5, 'poison' => 0.5, 'ground' => 1, 'flying' => 0.5,
            'psychic' => 2, 'bug' => 1, 'rock' => 1, 'ghost' => 0.5, 'dragon' => 1,
            'dark' => 2, 'steel' => 0.5, 'fairy' => 0.5
        ],
        'rock' => [
            'normal' => 1, 'fire' => 2, 'water' => 1, 'electric' => 1, 'grass' => 1,
            'ice' => 2, 'fighting' => 0.5, 'poison' => 1, 'ground' => 0.5, 'flying' => 2,
            'psychic' => 1, 'bug' => 2, 'rock' => 1, 'ghost' => 1, 'dragon' => 1,
            'dark' => 1, 'steel' => 0.5, 'fairy' => 1
        ],
        'ghost' => [
            'normal' => 0, 'fire' => 1, 'water' => 1, 'electric' => 1, 'grass' => 1,
            'ice' => 1, 'fighting' => 1, 'poison' => 1, 'ground' => 1, 'flying' => 1,
            'psychic' => 2, 'bug' => 1, 'rock' => 1, 'ghost' => 2, 'dragon' => 1,
            'dark' => 0.5, 'steel' => 1, 'fairy' => 1
        ],
        'dragon' => [
            'normal' => 1, 'fire' => 1, 'water' => 1, 'electric' => 1, 'grass' => 1,
            'ice' => 1, 'fighting' => 1, 'poison' => 1, 'ground' => 1, 'flying' => 1,
            'psychic' => 1, 'bug' => 1, 'rock' => 1, 'ghost' => 1, 'dragon' => 2,
            'dark' => 1, 'steel' => 0.5, 'fairy' => 0
        ],
        'dark' => [
            'normal' => 1, 'fire' => 1, 'water' => 1, 'electric' => 1, 'grass' => 1,
            'ice' => 1, 'fighting' => 0.5, 'poison' => 1, 'ground' => 1, 'flying' => 1,
            'psychic' => 2, 'bug' => 1, 'rock' => 1, 'ghost' => 2, 'dragon' => 1,
            'dark' => 0.5, 'steel' => 1, 'fairy' => 0.5
        ],
        'steel' => [
            'normal' => 1, 'fire' => 0.5, 'water' => 0.5, 'electric' => 0.5, 'grass' => 1,
            'ice' => 2, 'fighting' => 1, 'poison' => 1, 'ground' => 1, 'flying' => 1,
            'psychic' => 1, 'bug' => 1, 'rock' => 2, 'ghost' => 1, 'dragon' => 1,
            'dark' => 1, 'steel' => 0.5, 'fairy' => 2
        ],
        'fairy' => [
            'normal' => 1, 'fire' => 0.5, 'water' => 1, 'electric' => 1, 'grass' => 1,
            'ice' => 1, 'fighting' => 2, 'poison' => 0.5, 'ground' => 1, 'flying' => 1,
            'psychic' => 1, 'bug' => 1, 'rock' => 1, 'ghost' => 1, 'dragon' => 2,
            'dark' => 2, 'steel' => 0.5, 'fairy' => 1
        ]
    ];

    // Nomes dos tipos em português
    public static $typeNamesInPortuguese = [
        'normal' => 'Normal',
        'fire' => 'Fogo',
        'water' => 'Água',
        'electric' => 'Elétrico',
        'grass' => 'Grama',
        'ice' => 'Gelo',
        'fighting' => 'Lutador',
        'poison' => 'Venenoso',
        'ground' => 'Terra',
        'flying' => 'Voador',
        'psychic' => 'Psíquico',
        'bug' => 'Inseto',
        'rock' => 'Pedra',
        'ghost' => 'Fantasma',
        'dragon' => 'Dragão',
        'dark' => 'Sombrio',
        'steel' => 'Aço',
        'fairy' => 'Fada'
    ];

    // Cores dos tipos para CSS
    public static $typeColors = [
        'normal' => '#A8A878',
        'fire' => '#F08030',
        'water' => '#6890F0',
        'electric' => '#F8D030',
        'grass' => '#78C850',
        'ice' => '#98D8D8',
        'fighting' => '#C03028',
        'poison' => '#A040A0',
        'ground' => '#E0C068',
        'flying' => '#A890F0',
        'psychic' => '#F85888',
        'bug' => '#A8B820',
        'rock' => '#B8A038',
        'ghost' => '#705898',
        'dragon' => '#7038F8',
        'dark' => '#705848',
        'steel' => '#B8B8D0',
        'fairy' => '#EE99AC'
    ];

    /**
     * Normaliza os tipos para um array simples de strings
     * 
     * @param mixed $types Array de tipos (pode vir em diferentes formatos)
     * @return array
     */
    public static function normalizeTypes($types)
    {
        if (empty($types)) {
            return [];
        }

        // Se for string, tentar decodificar JSON
        if (is_string($types)) {
            $types = json_decode($types, true);
            if (!is_array($types)) {
                return [];
            }
        }

        // Se não for array, retornar vazio
        if (!is_array($types)) {
            return [];
        }

        $normalized = [];
        
        foreach ($types as $type) {
            // Se for string direta (customizado)
            if (is_string($type)) {
                $normalized[] = strtolower($type);
            }
            // Se for array com 'name' (formato simples)
            elseif (is_array($type) && isset($type['name'])) {
                $normalized[] = strtolower($type['name']);
            }
            // Se for array com 'type' (formato da PokeAPI)
            elseif (is_array($type) && isset($type['type']['name'])) {
                $normalized[] = strtolower($type['type']['name']);
            }
            // Se for array com índice 'name' dentro de 'type'
            elseif (is_array($type) && isset($type['type']) && is_array($type['type']) && isset($type['type']['name'])) {
                $normalized[] = strtolower($type['type']['name']);
            }
        }
        
        return array_unique($normalized);
    }

    /**
     * Calcula fraquezas e resistências baseado nos tipos do Pokémon
     * 
     * @param mixed $types Array de tipos (pode vir em diferentes formatos)
     * @return array
     */
    public static function getWeaknessesAndResistances($types)
    {
        // Normalizar os tipos primeiro
        $normalizedTypes = self::normalizeTypes($types);
        
        if (empty($normalizedTypes)) {
            return [
                'weaknesses' => [],
                'resistances' => [],
                'immunities' => []
            ];
        }
        
        $multipliers = [];
        
        foreach (self::$typeChart as $attackType => $defenseChart) {
            $multiplier = 1;
            foreach ($normalizedTypes as $type) {
                $typeKey = strtolower($type);
                if (isset($defenseChart[$typeKey])) {
                    $multiplier *= $defenseChart[$typeKey];
                }
            }
            
            if ($multiplier != 1) {
                $multipliers[$attackType] = $multiplier;
            }
        }
        
        $weaknesses = [];
        $resistances = [];
        $immunities = [];
        
        foreach ($multipliers as $type => $multiplier) {
            $displayName = self::$typeNamesInPortuguese[$type] ?? ucfirst($type);
            if ($multiplier == 0) {
                $immunities[] = $displayName;
            } elseif ($multiplier > 1) {
                $weaknesses[] = ['name' => $displayName, 'multiplier' => $multiplier];
            } elseif ($multiplier < 1) {
                $resistances[] = ['name' => $displayName, 'multiplier' => $multiplier];
            }
        }
        
        // Ordenar fraquezas da maior para a menor
        usort($weaknesses, function($a, $b) {
            return $b['multiplier'] <=> $a['multiplier'];
        });
        
        // Ordenar resistências da menor para a maior
        usort($resistances, function($a, $b) {
            return $a['multiplier'] <=> $b['multiplier'];
        });
        
        return [
            'weaknesses' => $weaknesses,
            'resistances' => $resistances,
            'immunities' => $immunities
        ];
    }

    /**
     * Tipos que o Pokémon é forte contra (STAB + super efetivo)
     * 
     * @param mixed $types Array de tipos (pode vir em diferentes formatos)
     * @return array
     */
    public static function getStrongAgainst($types)
    {
        // Normalizar os tipos primeiro
        $normalizedTypes = self::normalizeTypes($types);
        
        if (empty($normalizedTypes)) {
            return [];
        }
        
        $strongAgainst = [];
        
        foreach ($normalizedTypes as $pokemonType) {
            $typeKey = strtolower($pokemonType);
            if (isset(self::$typeChart[$typeKey])) {
                foreach (self::$typeChart[$typeKey] as $defenseType => $multiplier) {
                    if ($multiplier > 1 && !in_array($defenseType, $strongAgainst)) {
                        $strongAgainst[] = $defenseType;
                    }
                }
            }
        }
        
        $result = array_map(function($type) {
            return self::$typeNamesInPortuguese[$type] ?? ucfirst($type);
        }, $strongAgainst);
        
        sort($result);
        
        return $result;
    }
    
    /**
     * Obtém as cores dos tipos para CSS
     * 
     * @param string $type
     * @return string
     */
    public static function getTypeColor($type)
    {
        $typeKey = strtolower($type);
        return self::$typeColors[$typeKey] ?? '#A8A878';
    }
    
    /**
     * Obtém o nome do tipo em português
     * 
     * @param string $type
     * @return string
     */
    public static function getTypeNamePortuguese($type)
    {
        $typeKey = strtolower($type);
        return self::$typeNamesInPortuguese[$typeKey] ?? ucfirst($type);
    }
}