<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStorageAssignmentsTable extends Migration {
    public function up() {
        Schema::create('storage_assignments', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->unsignedInteger('material_id');
            $table->enum('storage', ['odontology', 'CAE']);
            $table->enum('storage_type', ['use', 'reserve']);

            $table->primary(['material_id', 'storage', 'storage_type']);

            $table->foreign(['material_id', 'storage'])
                ->references(['material_id', 'storage'])
                ->on('storages')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->index(['material_id', 'storage'], 'idx_storage_assignments_material_storage');
            $table->index('storage_type', 'idx_storage_assignments_type');
        });
    }

    public function down() {
        Schema::dropIfExists('storage_assignments');
    }
}