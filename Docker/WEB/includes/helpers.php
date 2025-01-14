<?php

function badgeEvolution($value1, $value2, string $suffix = ""): string
{
    $value1 = (float) ($value1 ?? 0);
    $value2 = (float) ($value2 ?? 0);

    if ($value1 === $value2) {
        return "0" . $suffix;
    }

    if ($value1 > $value2) {
        return "<span class='text-success fw-medium'>+" . formatNumber($value1 - $value2) . $suffix . "</span>";
    }

    return "<span class='text-danger fw-medium'>-" . formatNumber($value2 - $value1) . $suffix . "</span>";
}

function formatNumber(string $number): string
{
    // Vérifie s'il y a plus de 3 chiffres après la virgule
    if (strpos($number, '.') !== false && strlen(explode('.', $number)[1]) > 3) {
        $number = number_format((float)$number, 3, ',', '');

        // delete the last 0
        $number = rtrim($number, '0');
        // delete the last comma
        return rtrim($number, ',');
    }
    // delete the last 0
    $number = rtrim($number, '0');
    // delete the last comma
    $number = rtrim($number, ',');
    // Retourne le nombre tel quel s'il a 3 chiffres ou moins après la virgule
    return str_replace('.', ',', $number);
}