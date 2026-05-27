<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_expert')->default(false)->after('role');
        });

        Schema::table('answers', function (Blueprint $table) {
            $table->boolean('is_verified')->default(false)->after('is_accepted');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_expert');
        });

        Schema::table('answers', function (Blueprint $table) {
            $table->dropColumn('is_verified');
        });
    }
};
