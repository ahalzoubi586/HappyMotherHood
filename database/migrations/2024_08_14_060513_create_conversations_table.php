<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->engine = 'InnoDB'; // Set the table engine to InnoDB

            $table->id();
            $table->unsignedBigInteger('first_user_id');
            $table->unsignedBigInteger('second_user_id');
            $table->text('last_message')->nullable();
            $table->timestamp('last_message_time')->nullable();
            $table->boolean('last_message_read')->default(false);
            $table->timestamps();

            $table->foreign('first_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('second_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
