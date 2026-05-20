<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoragesTable extends Migration {
    public function up() {
        
        Schema::create('storages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('material_id');
            $table->enum('storage', ['odontology','CAE']);
            $table->string('qr_path')->nullable();

            $table->primary(['material_id', 'storage']);

            $table->foreign('material_id')
                ->references('material_id')->on('materials')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('storages');
    }
}