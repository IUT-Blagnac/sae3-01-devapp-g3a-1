<?php

function badgeEvolution($value1, $value2, string $suffix = ""): string
{
    $value1 = (float) ($value1 ?? 0);
    $value2 = (float) ($value2 ?? 0);

    if ($value1 === $value2) {
        return "0" . $suffix;
    }

    if ($value1 > $value2) {
        return "<span class='text-success fw-medium'>+" . ($value1 - $value2) . $suffix . "</span>";
    }

    return "<span class='text-danger fw-medium'>-" . ($value2 - $value1) . $suffix . "</span>";
}