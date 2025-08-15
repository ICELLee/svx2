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
        Schema::create('user_tool_entitlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('tool');                 // "bonushunt" | "slottracker" | "tournament" | "slotrequest"
            $table->timestamp('expires_at')->nullable(); // null = unbefristet (falls gewünscht)
            $table->foreignId('last_code_id')->nullable()->constrained('promo_codes')->nullOnDelete();
            $table->timestamps();
            $table->unique(['user_id','tool']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_tool_entitlements');
    }
};
