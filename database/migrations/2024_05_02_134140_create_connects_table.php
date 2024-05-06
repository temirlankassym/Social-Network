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
        Schema::create('connects', function (Blueprint $table) {
            $table->id();
            $table->string('follower');
            $table->string('followed');
            $table->timestamps();

            $table->foreign('follower')->references('username')->on('profiles')->onDelete('cascade');
            $table->foreign('followed')->references('username')->on('profiles')->onDelete('cascade');

            $table->unique(['follower', 'followed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connects');
    }
};
