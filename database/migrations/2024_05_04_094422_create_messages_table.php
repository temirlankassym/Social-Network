<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('sender');
            $table->string('receiver');
            $table->string('text');
            $table->timestamps();

            $table->foreign('sender')->references('username')->on('profiles')->onDelete('cascade');
            $table->foreign('receiver')->references('username')->on('profiles')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('messages');
    }
};
