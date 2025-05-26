<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmploymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');
            // Employment Details
            $table->string('department');
            $table->string('position');
            $table->date('date_hired');
            $table->decimal('monthly_basic_salary', 12, 2);
            $table->string('payroll_account_number');
            // Bank / Disbursement Info
            $table->string('bank_name');
            $table->string('bank_account_number');
            // Document uploads
            $table->string('gov_id_path');
            $table->string('payslip_path');
            $table->string('photo_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employment_details');
    }
}
