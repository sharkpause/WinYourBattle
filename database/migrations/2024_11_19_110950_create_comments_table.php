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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('body');
            $table->foreignID('user_id')->constrained()->onDelete('cascade');
            $table->foreignID('post_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('like_count')->default(0);
            $table->unsignedBigInteger('dislike_count')->default(0);

            $table->index('created_at');
            $table->index('post_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
