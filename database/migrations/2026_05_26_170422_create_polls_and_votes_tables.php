<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->json('options');
            $table->timestamps();
        });

        Schema::create('poll_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $table->integer('option_index');
            $table->timestamps();
            
            $table->unique(['user_id', 'poll_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('streak')->default(1);
            $table->timestamp('last_active_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['streak', 'last_active_at']);
        });
        
        Schema::dropIfExists('poll_votes');
        Schema::dropIfExists('polls');
    }
};
