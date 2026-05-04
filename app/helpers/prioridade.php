<?php

declare(strict_types=1);

function horasPorNivel(string $nivel): int
{
    return match($nivel) {
        'critico' => 3,
        'alta'    => 7,
        'medio'   => 20,
        'baixo'   => 48,
        default   => 48,
    };
}

function corPorNivel(string $nivel): string
{
    return match($nivel) {
        'critico' => 'bg-red-500',
        'alta'    => 'bg-orange-400',
        'medio'   => 'bg-yellow-400',
        'baixo'   => 'bg-green-500',
        default   => 'bg-gray-300',
    };
}

function labelPorNivel(string $nivel): string
{
    return match($nivel) {
        'critico' => 'Crítico (1h–3h)',
        'alta'    => 'Alta (4h–7h)',
        'medio'   => 'Médio (8h–20h)',
        'baixo'   => 'Baixo (24h+)',
        default   => 'Indefinido',
    };
}