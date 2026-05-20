<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStorageReserveTable extends Migration {
    public function up() {
        Schema::create('storage_reserve', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->unsignedInteger('material_id');
            $table->enum('storage', ['odontology', 'CAE']);
            
            $table->unsignedInteger('units')->default(0);
            $table->unsignedInteger('min_units')->default(0);
            $table->string('cabinet', 30);
            $table->unsignedInteger('shelf');

            $table->primary(['material_id', 'storage']);

            $table->foreign(['material_id', 'storage'])
                ->references(['material_id', 'storage'])
                ->on('storages')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('storage_reserve');
    }
}