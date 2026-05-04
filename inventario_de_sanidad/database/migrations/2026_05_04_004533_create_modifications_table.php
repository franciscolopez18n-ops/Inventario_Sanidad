<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModificationsTable extends Migration {

    public function up() {
        Schema::create('modifications', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('material_id');
            $table->enum('storage',['odontology','CAE']);
            $table->enum('storage_type', ['use', 'reserve']);
            $table->dateTime('action_datetime');
            $table->integer('units');
            $table->primary(['user_id', 'material_id', 'storage_type', 'storage', 'action_datetime'], 'pk_modifications');

            $table->foreign('user_id')
                ->references('user_id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign(['material_id', 'storage', 'storage_type'])
                ->references(['material_id', 'storage', 'storage_type'])->on('storages')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->index('user_id', 'idx_modifications_user');
            $table->index('storage_type', 'idx_modifications_storage_type');
            $table->index('action_datetime', 'idx_modifications_datetime');
            $table->index(['user_id', 'action_datetime'], 'idx_modifications_user_datetime');
        });
    }

    public function down() {
        Schema::dropIfExists('modifications');
    }
}
