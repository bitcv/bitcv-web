<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Project extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_cn', 200)->default('');
            $table->string('name_en', 200)->default('');
            $table->string('name_short', 100)->default('');
            $table->string('logo_url', 300)->default('');
            $table->string('banner_url', 300)->default('');
            $table->string('title', 100)->default('');
            $table->string('abstract', 1000)->default('');
            $table->string('white_paper_url', 300)->default('');
            $table->string('web_url', 300)->default('');
            $table->unsignedInteger('view_times')->default(0);
            $table->unsignedInteger('token_id')->default(0);
            $table->string('plan_amount', 32)->default('');
            $table->string('node_amount', 32)->default('');
            $table->string('total_amount', 32)->default('');
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedInteger('admin_id')->default(0);
            $table->unsignedInteger('region')->default(0);
            $table->unsignedTinyInteger('bussiness_type')->default(0);
            $table->unsignedTinyInteger('phrase')->default(0);
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->string('company_tel', 30)->default('');
            $table->string('company_email', 50)->default('');
            $table->string('company_addr', 100)->default('');
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
        Schema::dropIfExists('project');
    }
}
