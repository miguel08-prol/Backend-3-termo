<?php

return [
    'turnos' => [
        'manha' => [
            'horario_inicio' => '07:30',
            'horario_fim' => '11:30',
            'aulas' => [
                1 => ['inicio' => '07:30', 'fim' => '08:20'],
                2 => ['inicio' => '08:20', 'fim' => '09:10'],
                3 => ['inicio' => '09:10', 'fim' => '10:00'],
                4 => ['inicio' => '10:00', 'fim' => '10:50'],
                5 => ['inicio' => '10:50', 'fim' => '11:40'],
            ]
        ],
        'tarde' => [
            'horario_inicio' => '13:00',
            'horario_fim' => '17:00',
            'aulas' => [
                1 => ['inicio' => '13:00', 'fim' => '13:50'],
                2 => ['inicio' => '13:50', 'fim' => '14:40'],
                3 => ['inicio' => '14:40', 'fim' => '15:30'],
                4 => ['inicio' => '15:30', 'fim' => '16:20'],
                5 => ['inicio' => '16:20', 'fim' => '17:10'],
            ]
        ],
        'noite' => [
            'horario_inicio' => '18:30',
            'horario_fim' => '22:30',
            'aulas' => [
                1 => ['inicio' => '18:30', 'fim' => '19:20'],
                2 => ['inicio' => '19:20', 'fim' => '20:10'],
                3 => ['inicio' => '20:10', 'fim' => '21:00'],
                4 => ['inicio' => '21:00', 'fim' => '21:50'],
                5 => ['inicio' => '21:50', 'fim' => '22:40'],
            ]
        ]
    ]
];