<?php

if (!function_exists('flash_push')) {
    function flash_push(string $key, string ...$values): void {
        $current = session()->get($key, []);

        if (!is_array($current)) {
            $current = [];
        }

        array_push($current, ...$values);

        session()->flash($key, $current);
    }
}

class TextCase {
    const CAPITALIZED = 'capitalized';
    const LOWERCASE = 'lowercase';
    const UPPERCASE = 'uppercase';
}

class DisplayCategory {
    const STORAGE = 'storage';
    const MODALITY = 'modality';
}

if (!function_exists('apply_text_case')) {
    function apply_text_case(string $str, ?string $textCase): string {
        switch ($textCase) {
            case TextCase::LOWERCASE:
                return mb_strtolower($str);
            case TextCase::UPPERCASE:
                return mb_strtoupper($str);
            case TextCase::CAPITALIZED:
                return mb_strtoupper(mb_substr($str, 0, 1)) . mb_strtolower(mb_substr($str, 1));
            default:
                return $str;
        }
    }
}

if (!function_exists('display_name')) {
    function display_name(string $value, string $displayCategory, ?string $textCase = null): string {
        static $categoryMap = [
            DisplayCategory::STORAGE => ['CAE' => 'CAE', 'odontology' => 'Odontología'],
            DisplayCategory::MODALITY => ['use' => 'uso', 'reserve' => 'reserva'],
        ];

        return apply_text_case($categoryMap[$displayCategory][$value] ?? $value, $textCase);
    }
}