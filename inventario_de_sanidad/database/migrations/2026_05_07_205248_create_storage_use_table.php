<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStorageUseTable extends Migration {
    public function up() {
        Schema::create('storage_use', function (Blueprint $table) {
            $table->unsignedInteger('material_id');
            $table->enum('storage', ['odontology', 'CAE']);

            $table->unsignedInteger('units')->default(0);
            $table->unsignedInteger('min_units')->default(0);
            $table->unsignedInteger('cabinet');
            $table->unsignedInteger('shelf');
            $table->unsignedInteger('drawer');

            $table->primary(['material_id', 'storage']);

            $table->foreign(['material_id', 'storage'])
                ->references(['material_id', 'storage'])
                ->on('storages')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('storage_use');
    }
}
