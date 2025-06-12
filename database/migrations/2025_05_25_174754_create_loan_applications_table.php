<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->id();

            // Core links
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('loan_key');

            // Loan metadata
            $table->decimal('amount', 15, 2); // loan amount
            $table->integer('term'); // months

            $table->enum('status', ['pending', 'approved', 'rejected', 'released', 'fully_paid', 'cancelled'])->default('pending');
            $table->timestamp('status_changed_at')->nullable();

            // Applicant Info
            $table->string('last_name')->nullable();
            $table->string('given_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->date('application_date')->nullable();
            $table->text('address')->nullable();
            $table->enum('civil_status', ['Single', 'Married', 'Widowed/Separated'])->nullable();
            $table->string('nationality')->nullable();
            $table->string('contact_numbers')->nullable();
            $table->string('department')->nullable();
            $table->enum('employment_status', ['Permanent', 'Probationary', 'Other'])->nullable();
            $table->string('employment_status_other')->nullable();

            // Loan Form Data
            $table->text('amount_in_words')->nullable();
            $table->enum('loan_type', ['New Loan', 'Re-Loan'])->nullable();
            $table->date('repayment_start')->nullable();
            $table->enum('repayment_mode', ['Salary Deduction', 'Over-The-Counter'])->nullable();
            $table->decimal('repayment_amount', 15, 2)->nullable();

            // Extra Info
            $table->text('mortgage_details')->nullable();
            $table->text('withdrawal_authorization')->nullable();

            // File uploads (stored as file paths)
            $table->string('form_path')->nullable();
            $table->string('member_signature_file')->nullable();
            $table->string('comaker_signature_file')->nullable();
            $table->string('notary_file')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_applications');
    }
};
