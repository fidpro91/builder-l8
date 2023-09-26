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
        Schema::create('table_generator', function (Blueprint $table) {
            $table->increments('id');
            $table->string('schema_name', 50)->nullable();
            $table->string('table_name', 100);
            $table->string('table_element', 50);
            $table->timestamp('created_at', 6)->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_generator');
    }
};
