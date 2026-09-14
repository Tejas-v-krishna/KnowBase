<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thanks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('answer_id')->constrained()->cascadeOnDelete();
            $table->text('message')->nullable();
            $table->unsignedInteger('xp_amount')->default(0);
            $table->timestamp('created_at')->nullable();

            $table->unique(['user_id', 'answer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thanks');
    }
};
