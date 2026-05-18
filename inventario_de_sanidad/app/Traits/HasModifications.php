<?php

namespace App\Traits;

trait HasModifications {
    public function getExactModifications() {
        return;
        /*return Modification::where('material_id', $this->material_id)
            ->where('storage', $this->storage)
            ->where('storage_type', $this->getModeLabel())
            ->get();*/
    }
}