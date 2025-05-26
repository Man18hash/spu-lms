<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expected_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_application_id')
                  ->constrained('loan_applications')
                  ->onDelete('cascade');
            $table->date('due_date');
            $table->decimal('amount_due', 12, 2);
            $table->integer('months_lapsed')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expected_schedules');
    }
};
