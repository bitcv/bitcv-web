<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProjMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proj_media', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('proj_id')->default(0);
            $table->unsignedInteger('media_id')->default(0);
            $table->string('media_url', 300)->default('');
            $table->string('image_url', 300)->default('');
            $table->string('title', 300)->default('');
            $table->string('detail', 1000)->default('');
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
        Schema::dropIfExists('proj_media');
    }
}
