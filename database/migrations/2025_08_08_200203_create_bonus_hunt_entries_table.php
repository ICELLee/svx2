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
        Schema::create('bonus_hunt_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bonus_hunt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('slot_id')->constrained('slots')->cascadeOnDelete();
            $table->unsignedInteger('position')->default(1);
            $table->decimal('bet', 10, 2)->default(0);
            $table->decimal('win', 10, 2)->nullable();
            $table->decimal('x_value', 10, 2)->nullable();
            $table->boolean('bonus_bought')->default(false);
            $table->decimal('bonus_cost', 10, 2)->nullable();
            $table->timestamps();

            $table->index(['bonus_hunt_id','position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonus_hunt_entries');
    }
};
