<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaveCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('save_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("card_name");
            $table->string("user_id");
            $table->string("card_token");
            $table->string("role");
            $table->string("description",600);
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
        Schema::dropIfExists('save_cards');
    }
}
