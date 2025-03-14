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
        Schema::create('followings', function (Blueprint $table) {
            $table->id();
                
            $table->foreignID('user_id')->on('users');
            $table->foreignID('following_id')->on('users');

            $table->timestamps();

            $table->unique(['user_id', 'following_id']);
            
            $table->index('user_id');
            $table->index(['user_id', 'following_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('followings');
    }
};
