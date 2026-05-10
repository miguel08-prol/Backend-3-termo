<?php

namespace App\Helpers;

class BattleHelper
{
    // Tabela de efetividade de tipos (2x, 0.5x, 0x)
    private static $typeChart = [
        'normal' => ['rock' => 0.5, 'ghost' => 0, 'steel' => 0.5],
        'fire' => ['fire' => 0.5, 'water' => 0.5, 'grass' => 2, 'ice' => 2, 'bug' => 2, 'rock' => 0.5, 'dragon' => 0.5, 'steel' => 2],
        'water' => ['fire' => 2, 'water' => 0.5, 'grass' => 0.5, 'ground' => 2, 'rock' => 2, 'dragon' => 0.5],
        'electric' => ['water' => 2, 'electric' => 0.5, 'grass' => 0.5, 'ground' => 0, 'flying' => 2, 'dragon' => 0.5],
        'grass' => ['fire' => 0.5, 'water' => 2, 'grass' => 0.5, 'poison' => 0.5, 'ground' => 2, 'flying' => 0.5, 'bug' => 0.5, 'rock' => 2, 'dragon' => 0.5, 'steel' => 0.5],
        'ice' => ['fire' => 0.5, 'water' => 0.5, 'ice' => 0.5, 'fighting' => 0.5, 'ground' => 2, 'flying' => 2, 'grass' => 2, 'dragon' => 2, 'steel' => 0.5],
        'fighting' => ['normal' => 2, 'ice' => 2, 'poison' => 0.5, 'flying' => 0.5, 'psychic' => 0.5, 'bug' => 0.5, 'rock' => 2, 'ghost' => 0, 'dark' => 2, 'steel' => 2, 'fairy' => 0.5],
        'poison' => ['grass' => 2, 'fighting' => 2, 'poison' => 0.5, 'ground' => 0.5, 'rock' => 0.5, 'ghost' => 0.5, 'steel' => 0, 'fairy' => 2],
        'ground' => ['fire' => 2, 'electric' => 2, 'grass' => 0.5, 'poison' => 2, 'flying' => 0, 'bug' => 0.5, 'rock' => 2, 'steel' => 2],
        'flying' => ['electric' => 0.5, 'fighting' => 2, 'bug' => 2, 'rock' => 0.5, 'steel' => 0.5, 'grass' => 2],
        'psychic' => ['fighting' => 2, 'poison' => 2, 'psychic' => 0.5, 'dark' => 0, 'steel' => 0.5],
        'bug' => ['fire' => 0.5, 'grass' => 2, 'fighting' => 0.5, 'poison' => 0.5, 'flying' => 0.5, 'psychic' => 2, 'ghost' => 0.5, 'dark' => 2, 'steel' => 0.5, 'fairy' => 0.5],
        'rock' => ['fire' => 2, 'ice' => 2, 'fighting' => 0.5, 'ground' => 0.5, 'flying' => 2, 'bug' => 2, 'steel' => 0.5],
        'ghost' => ['normal' => 0, 'psychic' => 2, 'ghost' => 2, 'dark' => 0.5],
        'dragon' => ['dragon' => 2, 'steel' => 0.5, 'fairy' => 0],
        'dark' => ['fighting' => 0.5, 'psychic' => 2, 'ghost' => 2, 'dark' => 0.5, 'fairy' => 0.5],
        'steel' => ['fire' => 0.5, 'water' => 0.5, 'electric' => 0.5, 'ice' => 2, 'rock' => 2, 'steel' => 0.5, 'fairy' => 2],
        'fairy' => ['fire' => 0.5, 'fighting' => 2, 'poison' => 0.5, 'dragon' => 2, 'dark' => 2, 'steel' => 0.5],
    ];

    /**
     * Calcula o multiplicador de dano baseado nos tipos
     */
    public static function calculateTypeMultiplier($attackerTypes, $defenderTypes)
    {
        $multiplier = 1;
        
        foreach ($attackerTypes as $attackType) {
            foreach ($defenderTypes as $defendType) {
                if (isset(self::$typeChart[$attackType][$defendType])) {
                    $multiplier *= self::$typeChart[$attackType][$defendType];
                }
            }
        }
        
        return $multiplier;
    }

    /**
     * Calcula o score de batalha de um Pokémon
     */
    public static function calculateBattleScore($pokemon)
    {
        $stats = $pokemon['stats'] ?? [];
        $totalStats = 0;
        
        foreach ($stats as $stat) {
            $baseStat = is_array($stat) ? ($stat['base_stat'] ?? $stat['stat']['base_stat'] ?? 0) : 0;
            $totalStats += $baseStat;
        }
        
        // Fórmula: (Total Stats / 780) * 100 + bônus de tipos
        $baseScore = ($totalStats / 780) * 100;
        
        // Bônus para Pokémon com tipos raros
        $typeBonus = 0;
        $types = $pokemon['types'] ?? [];
        $rareTypes = ['dragon', 'ghost', 'dark', 'steel', 'fairy'];
        
        foreach ($types as $type) {
            if (in_array($type, $rareTypes)) {
                $typeBonus += 5;
            }
        }
        
        return min(100, $baseScore + $typeBonus);
    }

    /**
     * Simula uma batalha entre dois Pokémon
     */
    public static function simulateBattle($pokemon1, $pokemon2)
    {
        // Obter tipos
        $types1 = self::extractTypes($pokemon1);
        $types2 = self::extractTypes($pokemon2);
        
        // Calcular efetividade
        $effectiveness1 = self::calculateTypeMultiplier($types1, $types2);
        $effectiveness2 = self::calculateTypeMultiplier($types2, $types1);
        
        // Calcular poder base (usando stats)
        $power1 = self::calculatePower($pokemon1);
        $power2 = self::calculatePower($pokemon2);
        
        // Calcular dano final
        $damage1 = $power1 * $effectiveness1;
        $damage2 = $power2 * $effectiveness2;
        
        // Fator sorte (pequena variação)
        $luck1 = rand(85, 115) / 100;
        $luck2 = rand(85, 115) / 100;
        
        $finalDamage1 = $damage1 * $luck1;
        $finalDamage2 = $damage2 * $luck2;
        
        // HP efetivo (considerando defesa)
        $hp1 = self::getStat($pokemon1, 'hp');
        $hp2 = self::getStat($pokemon2, 'hp');
        
        $defense1 = self::getStat($pokemon1, 'defense');
        $defense2 = self::getStat($pokemon2, 'defense');
        
        $effectiveHp1 = $hp1 * (1 + $defense2 / 100);
        $effectiveHp2 = $hp2 * (1 + $defense1 / 100);
        
        // Turnos para derrotar
        $turnsToWin1 = $effectiveHp2 / max(1, $finalDamage1);
        $turnsToWin2 = $effectiveHp1 / max(1, $finalDamage2);
        
        $winner = $turnsToWin1 < $turnsToWin2 ? 1 : 2;
        $margin = abs($turnsToWin1 - $turnsToWin2) / max($turnsToWin1, $turnsToWin2);
        
        // Nível de dificuldade da vitória
        $difficulty = 'normal';
        if ($margin > 0.3) {
            $difficulty = $margin > 0.5 ? 'fácil' : 'média';
        } elseif ($margin > 0.1) {
            $difficulty = 'apertada';
        } else {
            $difficulty = 'equilibrada';
        }
        
        return [
            'winner' => $winner,
            'winner_name' => $winner == 1 ? $pokemon1['name'] : $pokemon2['name'],
            'effectiveness1' => $effectiveness1,
            'effectiveness2' => $effectiveness2,
            'damage1' => round($finalDamage1, 1),
            'damage2' => round($finalDamage2, 1),
            'turns1' => round($turnsToWin1, 1),
            'turns2' => round($turnsToWin2, 1),
            'difficulty' => $difficulty,
            'score1' => self::calculateBattleScore($pokemon1),
            'score2' => self::calculateBattleScore($pokemon2),
        ];
    }

    /**
     * Extrai tipos do Pokémon
     */
    private static function extractTypes($pokemon)
    {
        $types = [];
        $rawTypes = $pokemon['types'] ?? [];
        
        if (is_string($rawTypes)) {
            $rawTypes = json_decode($rawTypes, true);
        }
        
        foreach ($rawTypes as $type) {
            if (is_string($type)) {
                $types[] = $type;
            } elseif (is_array($type) && isset($type['name'])) {
                $types[] = $type['name'];
            } elseif (is_array($type) && isset($type['type']['name'])) {
                $types[] = $type['type']['name'];
            }
        }
        
        return $types;
    }

    /**
     * Obtém um stat específico
     */
    private static function getStat($pokemon, $statName)
    {
        $stats = $pokemon['stats'] ?? [];
        
        foreach ($stats as $stat) {
            $name = is_array($stat) ? ($stat['stat']['name'] ?? $stat['stat'] ?? '') : '';
            $value = is_array($stat) ? ($stat['base_stat'] ?? 50) : 50;
            
            if ($name === $statName) {
                return $value;
            }
        }
        
        return 50; // valor padrão
    }

    /**
     * Calcula o poder de ataque baseado nos stats
     */
    private static function calculatePower($pokemon)
    {
        $attack = self::getStat($pokemon, 'attack');
        $specialAttack = self::getStat($pokemon, 'special-attack');
        $speed = self::getStat($pokemon, 'speed');
        
        // Média ponderada: ataque físico e especial + bônus de velocidade
        return ($attack * 0.4) + ($specialAttack * 0.4) + ($speed * 0.2);
    }

    /**
     * Prepara os dados para o gráfico de radar
     */
    public static function prepareRadarData($pokemon)
    {
        $stats = $pokemon['stats'] ?? [];
        $statMap = [
            'hp' => 0,
            'attack' => 0,
            'defense' => 0,
            'special-attack' => 0,
            'special-defense' => 0,
            'speed' => 0,
        ];
        
        foreach ($stats as $stat) {
            $name = is_array($stat) ? ($stat['stat']['name'] ?? $stat['stat'] ?? '') : '';
            $value = is_array($stat) ? ($stat['base_stat'] ?? 50) : 50;
            
            if (isset($statMap[$name])) {
                $statMap[$name] = $value;
            }
        }
        
        return [
            'labels' => ['HP', 'Ataque', 'Defesa', 'Ataque Especial', 'Defesa Especial', 'Velocidade'],
            'datasets' => [
                [
                    'label' => ucfirst($pokemon['name']),
                    'data' => array_values($statMap),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 2,
                    'pointBackgroundColor' => 'rgba(59, 130, 246, 1)',
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor' => 'rgba(59, 130, 246, 1)',
                ]
            ]
        ];
    }

    /**
     * Prepara dados para comparação de dois Pokémon
     */
    public static function prepareComparisonData($pokemon1, $pokemon2)
    {
        $stats1 = self::prepareRadarData($pokemon1);
        $stats2 = self::prepareRadarData($pokemon2);
        
        // Combinar datasets
        $stats1['datasets'][] = [
            'label' => ucfirst($pokemon2['name']),
            'data' => $stats2['datasets'][0]['data'],
            'backgroundColor' => 'rgba(168, 85, 247, 0.2)',
            'borderColor' => 'rgba(168, 85, 247, 1)',
            'borderWidth' => 2,
            'pointBackgroundColor' => 'rgba(168, 85, 247, 1)',
            'pointBorderColor' => '#fff',
        ];
        
        return $stats1;
    }
}