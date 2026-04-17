<?php

namespace App\Enums;

enum FlashType: string {
    case SUCCESS = 'success';
    case ERROR = 'error';
    case WARNING = 'warning';
    case INFO = 'info';
}