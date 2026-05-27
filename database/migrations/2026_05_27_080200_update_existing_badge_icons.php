<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $mappings = [
            'pencil-alt' => '📝',
            'question-mark-circle' => '❓',
            'check-circle' => '✅',
            'sparkles' => '✨',
            'academic-cap' => '🎓',
            'trophy' => '🏆',
            'document-duplicate' => '📚',
            'lightning-bolt' => '⚡',
        ];

        foreach ($mappings as $oldIcon => $newEmoji) {
            DB::table('badges')
                ->where('icon', $oldIcon)
                ->update(['icon' => $newEmoji]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $mappings = [
            '📝' => 'pencil-alt',
            '❓' => 'question-mark-circle',
            '✅' => 'check-circle',
            '✨' => 'sparkles',
            '🎓' => 'academic-cap',
            '🏆' => 'trophy',
            '📚' => 'document-duplicate',
            '⚡' => 'lightning-bolt',
        ];

        foreach ($mappings as $newEmoji => $oldIcon) {
            DB::table('badges')
                ->where('icon', $newEmoji)
                ->update(['icon' => $oldIcon]);
        }
    }
};
