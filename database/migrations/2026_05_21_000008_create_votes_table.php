<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('votable_type', 100);
            $table->unsignedBigInteger('votable_id');
            $table->tinyInteger('value'); // 1 or -1
            $table->timestamps();
            $table->unique(['user_id', 'votable_type', 'votable_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('votes');
    }
};
