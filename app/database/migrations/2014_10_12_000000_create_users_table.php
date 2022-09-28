<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id');
            $table->string('name')->comment('アカウント名');
            $table->string('email')->unique()->nullable(); //SNS認証のためnullable
            $table->string('profile_image')->nullable()->comment('プロフィール画像');
            $table->timestamp('email_verified_at')->nullable(); //メール認証用
            $table->string('password')->nullable(); //SNS認証のためnullable
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
