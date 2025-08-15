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
        Schema::create('slots', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('key')->unique();           // z.B. wanted-dead-or-a-wild
            $table->string('provider')->nullable()->index();
            $table->string('rtp')->nullable();         // als String belassen (z.B. '96.38')
            $table->string('max_win')->nullable();     // erlaubt '12500x'
            $table->string('image_url')->nullable();
            $table->decimal('personal_win', 12, 2)->nullable();
            $table->decimal('personal_bet', 12, 2)->nullable();
            $table->decimal('personal_x',   12, 2)->nullable();
            $table->boolean('is_active')->default(true)->index(); // für Aktiv/Deaktiv
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slots');
    }
};
