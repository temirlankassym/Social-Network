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
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('subscriber');
            $table->timestamps();

            $table->foreign('username')->references('username')->on('profiles')->onDelete('cascade');
            $table->foreign('subscriber')->references('username')->on('profiles')->onDelete('cascade');

            $table->unique(['username', 'subscriber']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
