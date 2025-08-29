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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('title');
            $table->longText('message');

            $table->enum('reason', [
                'Sonstiges','Partnerschaft','Test Code Erhalten','Allgemeine Frage',
                'Frage zu Produkt','Produkt Kaufen','Bug Report','User Report'
            ]);

            $table->json('products')->nullable(); // Mehrfachauswahl
            $table->enum('priority', ['green','orange','red','gold'])->default('green');
            $table->enum('status', ['offen','geschlossen','warte_info'])->default('offen');

            $table->string('extra_info')->nullable(); // z.B. Bug-Code oder UserID bei Reports
            $table->foreignId('claimed_by')->nullable()->constrained('users'); // Wer hat geclaimt?

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
