<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Only create if it doesn’t already exist
        if (! Schema::hasTable('repayments')) {
            Schema::create('repayments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('expected_schedule_id')
                      ->constrained('expected_schedules')
                      ->onDelete('cascade');
                $table->decimal('payment_amount', 12, 2)->default(0);
                $table->date('payment_date')->nullable();
                $table->string('or_number')->nullable();
                $table->date('or_date')->nullable();
                $table->decimal('penalty_amount', 12, 2)->default(0);
                $table->boolean('returned_check')->default(false);
                $table->boolean('deferred')->default(false);
                $table->string('remarks', 255)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('repayments');
    }
};
