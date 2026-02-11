<?php

function scoreCircle($data)
{
    if (
        !isset($data['points']) ||
        !is_array($data['points']) ||
        count($data['points']) < 5
    ) {
        return 0;
    }

    $points = $data['points'];
    $n = count($points);

    // === 1. Центр фигуры (centroid) ===
    $sumX = 0;
    $sumY = 0;

    foreach ($points as $p) {
        if (!isset($p['x']) || !isset($p['y'])) continue;
        $sumX += $p['x'];
        $sumY += $p['y'];
    }

    $cx = $sumX / $n;
    $cy = $sumY / $n;

    // === 2. Радиусы до центра ===
    $radii = [];

    foreach ($points as $p) {
        if (!isset($p['x']) || !isset($p['y'])) continue;

        $dx = $p['x'] - $cx;
        $dy = $p['y'] - $cy;

        $radii[] = sqrt($dx * $dx + $dy * $dy);
    }

    if (count($radii) < 5) return 0;

    // === 3. Средний радиус ===
    $r_mean = array_sum($radii) / count($radii);
    if ($r_mean <= 0) return 0;

    // === 4. Средняя нормализованная ошибка ===
    $error_sum = 0;

    foreach ($radii as $r) {
        $error_sum += abs($r - $r_mean) / $r_mean;
    }

    $mean_error = $error_sum / count($radii);

    // === 5. Итоговый скор ===
    $score = (1 - $mean_error) * 100;

    if ($score < 0) $score = 0;
    if ($score > 100) $score = 100;

    return round($score, 2);
}


