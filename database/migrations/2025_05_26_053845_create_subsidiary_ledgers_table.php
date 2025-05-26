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
        Schema::create('subsidiary_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_application_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->date('month');
            $table->decimal('payment_amount', 12, 2);
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subsidiary_ledgers');
    }
};
