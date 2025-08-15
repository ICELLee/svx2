<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bonus_hunts', function (Blueprint $table) {
            if (!Schema::hasColumn('bonus_hunts', 'public_token')) {
                $table->string('public_token', 64)->nullable()->unique()->after('is_active');
            }
        });

        // Backfill für bestehende Zeilen ohne Token
        DB::table('bonus_hunts')
            ->whereNull('public_token')
            ->orWhere('public_token', '')
            ->orderBy('id')
            ->chunkById(500, function ($rows) {
                foreach ($rows as $row) {
                    DB::table('bonus_hunts')
                        ->where('id', $row->id)
                        ->update(['public_token' => Str::random(40)]);
                }
            });

        // jetzt NOT NULL erzwingen
        Schema::table('bonus_hunts', function (Blueprint $table) {
            $table->string('public_token', 64)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bonus_hunts', function (Blueprint $table) {
            if (Schema::hasColumn('bonus_hunts', 'public_token')) {
                $table->dropUnique(['public_token']);
                $table->dropColumn('public_token');
            }
        });
    }
};
