<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialActivityTable extends Migration {
    
    public function up() {
        Schema::create('material_activity', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('activity_id');
            $table->unsignedInteger('material_id');
            $table->unsignedInteger('units');
            $table->primary(['activity_id', 'material_id']);

            $table->foreign('activity_id')
                  ->references('activity_id')->on('activities')
                  ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('material_id')
                  ->references('material_id')->on('materials')
                  ->onDelete('cascade')->onUpdate('cascade');

            $table->index('material_id', 'idx_material_activity_material');
        });
    }

    public function down() {
        Schema::dropIfExists('material_activity');
    }
}
