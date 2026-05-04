<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialsTable extends Migration {

    public function up() {
        Schema::create('materials', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('material_id');
            $table->string('name', 60);
            $table->string('description', 255);
            $table->string('image_path', 255)->nullable();
        });
    }

    public function down() {
        Schema::dropIfExists('materials');
    }
}
