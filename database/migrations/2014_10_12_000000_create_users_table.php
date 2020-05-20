<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('google_id')->nullable();
            $table->boolean('status')->default(1);
            $table->string('type')->default("web");
            $table->string('business_name')->nullable();
            $table->string('number')->nullable();
            $table->string('business_address')->nullable();
            $table->string('code')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
        //\Illuminate\Support\Facades\DB::statement('ALTER TABLE users AUTO_INCREMENT = 1000;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
