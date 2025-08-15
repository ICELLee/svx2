<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();                // Einlösecode
            $table->json('tools');                           // ["bonushunt","slottracker","tournament","slotrequest"]
            $table->unsignedInteger('duration_days');        // Laufzeit in Tagen pro Einlösung
            $table->boolean('is_locked')->default(false);    // Code sperren/entsperren
            $table->unsignedInteger('max_uses')->default(1); // Standard: 1 Nutzer pro Code
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('starts_at')->nullable();      // optional: Startzeit
            $table->timestamp('expires_at')->nullable();     // optional: Code selbst ablaufbar
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['is_locked','expires_at','starts_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
    }
};
