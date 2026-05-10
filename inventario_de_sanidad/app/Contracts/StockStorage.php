<?php

namespace App\Contracts;

interface StockStorage {
    public function getUnits(): int;
    public function getMinUnits(): int;
    public function getModeLabel(): string;
}