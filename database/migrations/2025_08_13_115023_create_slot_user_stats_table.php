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
        Schema::create('slot_user_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('slot_id')->constrained()->cascadeOnDelete();
            $table->decimal('personal_bet', 12, 2)->nullable();
            $table->decimal('personal_win', 12, 2)->nullable();
            $table->decimal('personal_x', 12, 4)->nullable();
            $table->timestamps();

            $table->unique(['user_id','slot_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slot_user_stats');
    }
};
