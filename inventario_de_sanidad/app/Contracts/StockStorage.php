<?php

namespace App\Contracts;

interface StockStorage {
    public function getUnits(): int;
    public function getMinUnits(): int;
    public function getCabinet(): string;
    public function getShelf(): int;
    public function getDrawer(): ?int;
}