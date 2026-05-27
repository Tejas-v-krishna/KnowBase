<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('collection_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained()->cascadeOnDelete();
            $table->string('collectable_type', 100);
            $table->unsignedBigInteger('collectable_id');
            $table->integer('order')->default(0);
            $table->timestamp('created_at')->nullable();
        });
    }
    public function down(): void {
        Schema::dropIfExists('collection_items');
    }
};
