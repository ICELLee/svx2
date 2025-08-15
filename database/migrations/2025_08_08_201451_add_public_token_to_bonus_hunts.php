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
                $table->ulid('public_token')->unique()->nullable()->after('id');
            }
            if (!Schema::hasColumn('bonus_hunts', 'is_active')) {
                $table->boolean('is_active')->default(false)->after('public_token');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bonus_hunts', function (Blueprint $table) {
            if (Schema::hasColumn('bonus_hunts', 'public_token')) {
                $table->dropColumn('public_token');
            }
            if (Schema::hasColumn('bonus_hunts', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
