<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug', 270)->unique();
            $table->longText('body');
            $table->enum('status', ['open', 'closed', 'resolved'])->default('open');
            $table->unsignedBigInteger('accepted_answer_id')->nullable();
            $table->integer('views_count')->default(0);
            $table->integer('answers_count')->default(0);
            $table->integer('votes_count')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('questions');
    }
};
