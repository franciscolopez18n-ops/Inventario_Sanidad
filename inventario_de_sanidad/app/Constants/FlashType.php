<?php

namespace App\Constants;

use ReflectionClass;

class FlashType {
    public const SUCCESS = 'success';
    public const ERROR = 'error';
    public const WARNING = 'warning';
    public const INFO = 'info';

    public static function cases(): array {
        return array_values((new ReflectionClass(static::class))->getConstants());
    }
}