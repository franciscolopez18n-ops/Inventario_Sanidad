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