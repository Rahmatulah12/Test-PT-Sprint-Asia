<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_menu_id');
            $table->foreign('category_menu_id')->references('id')->on('category_menus')->onUpdate('cascade');
            $table->string('name', 100)->nullable($value = false)->unqiue();
            $table->text('description')->nullable($value = true);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable($value = true)->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
