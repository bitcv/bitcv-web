<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProjMember extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proj_member', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('proj_id')->default(0);
            $table->string('photo_url')->default('');
            $table->string('name', 32)->default('');
            $table->string('position', 32)->default('');
            $table->string('intro', 1000)->default('');
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
        Schema::dropIfExists('proj_member');
    }
}
