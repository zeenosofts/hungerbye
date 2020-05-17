<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsPostedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_posteds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("user_id");
            $table->string("item_id");
            $table->string("item_description");
            $table->string("item_price");
            $table->string("customer_id");
//            $table->string("percent25")->nullable();
//            $table->string("percent50")->nullable();
//            $table->string("percent75")->nullable();
//            $table->string("percent100")->nullable();
            $table->string("status")->default(1)->nullable();
            $table->string("paymentNodes")->nullable();
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
        Schema::dropIfExists('items_posteds');
    }
}
