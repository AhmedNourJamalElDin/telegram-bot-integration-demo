<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();

            $table->string('username')->nullable();
            $table->string('chat_id')->nullable();

            $table->foreignId('user_id')->nullable()->constrained();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('chats');
    }
}
