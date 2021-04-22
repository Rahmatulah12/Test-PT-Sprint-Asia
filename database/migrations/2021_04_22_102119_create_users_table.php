<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->string('username',30)->nullable($value = false)->unique();
            $table->string('email', 35)->nullable($value = false)->unique();
            $table->string('password', 255)->nullable($value = false);
            $table->text('active_token')->nullable($value = true);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable($value =true)->default(null);
            $table->index(['username', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
