<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImgurTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imgur__tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->string('refresh_token');
            $table->string('client_id');
            $table->string('client_secret');
            $table->string('grant_type');
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
        Schema::dropIfExists('imgur__tokens');
    }
}
