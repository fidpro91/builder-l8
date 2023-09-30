<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('ms_menu')) {
            Schema::create('ms_menu', function (Blueprint $table) {
                $table->char('menu_code', 20);
                $table->string('menu_name', 140);
                $table->text('menu_url')->nullable();
                $table->integer('menu_parent_id')->nullable()->default(0);
                $table->boolean('menu_status')->nullable()->default(true);
                $table->string('menu_icon', 30)->nullable();
                $table->integer('modul_id')->nullable();
                $table->increments('menu_id');
                $table->string('menu_target', 20)->nullable();
                $table->string('menu_function')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ms_menu');
    }
};
