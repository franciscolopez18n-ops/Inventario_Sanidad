<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {

    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('user_id');
            $table->string('first_name', 40);
            $table->string('last_name', 60);
            $table->string('email', 100)->unique();
            $table->string('hashed_password', 255);
            $table->boolean('first_log')->default(false);
            $table->enum('user_type', ['student', 'teacher', 'admin']);
            $table->dateTime('created_at');
        });
    }

    public function down() {
        Schema::dropIfExists('users');
    }
}
