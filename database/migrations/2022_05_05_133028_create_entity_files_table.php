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
        Schema::create('entity_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_id')->nullable()->references('id')->on('entities');
            $table->string('name', 255);
            $table->string('path', 255);
            $table->integer('size');
            $table->string('type', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entity_files');
    }
};
