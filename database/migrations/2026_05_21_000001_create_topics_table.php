<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('slug', 110)->unique();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->integer('followers_count')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('topics');
    }
};
