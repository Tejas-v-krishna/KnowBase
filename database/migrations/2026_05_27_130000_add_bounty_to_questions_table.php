<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->unsignedInteger('bounty_amount')->default(0)->after('votes_count');
            $table->timestamp('bounty_expires_at')->nullable()->after('bounty_amount');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['bounty_amount', 'bounty_expires_at']);
        });
    }
};
