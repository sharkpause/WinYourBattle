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
        Schema::create('post_dislikes', function (Blueprint $table) {
            $table->id();
            $table->foreignID('user_id')->constrained()->onDelete('cascade');
            $table->foreignID('post_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'post_id']);

            $table->index('user_id');
            $table->index('post_id');
            $table->index(['user_id', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_dislikes');
    }
};
