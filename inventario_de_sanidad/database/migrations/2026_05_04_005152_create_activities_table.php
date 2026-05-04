<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration {
    
    public function up() {
        Schema::create('activities', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('activity_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('teacher_id');
            $table->string('title', 100);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_id')
                  ->references('user_id')->on('users')
                  ->onDelete('cascade')->onUpdate('cascade');

            $table->index('created_at', 'idx_activities_created_at');
        });
    }

    public function down() {
        Schema::dropIfExists('activities');
    }
}
