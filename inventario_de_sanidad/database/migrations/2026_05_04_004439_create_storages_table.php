<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoragesTable extends Migration {
    public function up() {
        
        Schema::create('storages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('material_id');
            $table->enum('storage',['odontology','CAE']);
            $table->enum('storage_type', ['use', 'reserve']);
            $table->string('cabinet', 30);
            $table->unsignedInteger('shelf');
            $table->unsignedInteger('drawer')->nullable();
            $table->unsignedInteger('units')->default(0);
            $table->unsignedInteger('min_units')->default(0);

            $table->primary(['material_id', 'storage', 'storage_type']);

            $table->foreign('material_id')
                ->references('material_id')->on('materials')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->index('material_id', 'idx_storages_material');
            $table->index('storage_type', 'idx_storages_type');
        });
    }

    public function down() {
        Schema::dropIfExists('storages');
    }
}
