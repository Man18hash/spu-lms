<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // alter the ENUM to include your new statuses
        DB::statement("
            ALTER TABLE `loan_applications`
            MODIFY `status` ENUM(
                'pending',
                'approved',
                'rejected',
                'released',
                'fully_paid',
                'cancelled'
            ) NOT NULL DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        // revert back to only the original three (adjust as needed)
        DB::statement("
            ALTER TABLE `loan_applications`
            MODIFY `status` ENUM(
                'pending',
                'approved',
                'rejected'
            ) NOT NULL DEFAULT 'pending'
        ");
    }
};
