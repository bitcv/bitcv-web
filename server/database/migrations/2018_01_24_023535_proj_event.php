<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProjEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proj_event', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('proj_id')->default(0);
            $table->timestamp('occur_time')->nullable();
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
        Schema::dropIfExists('proj_event');
    }
}
