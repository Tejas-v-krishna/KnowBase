<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description');
            $table->string('icon', 100);
            $table->string('criteria_type', 100); // e.g. 'reputation', 'answers_count'
            $table->integer('criteria_value');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('badges');
    }
};
