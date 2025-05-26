<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->string('loan_key');
            $table->string('form_path');
            $table->decimal('amount', 15, 2);
            $table->integer('term');
            $table->enum('status', ['pending','approved','rejected'])
                  ->default('pending');
            $table->timestamp('status_changed_at')
                  ->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('loan_applications');
    }
};
