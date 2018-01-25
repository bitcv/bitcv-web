<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProjPartner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proj_partner', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('proj_id')->default(0);
            $table->string('name', 64)->default('');
            $table->string('logo_url', 300)->default('');
            $table->string('web_url', 300)->default('');
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
        Schema::dropIfExists('proj_partner');
    }
}
