<?php

namespace App\Models\Traits;

use App\Models\xModification;

trait HasModifications {
    public function getExactModifications() {
        return xModification::where('material_id', $this->material_id)
            ->where('storage', $this->storage)
            ->where('storage_type', $this->getModeLabel())
            ->get();
    }
}