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
        Schema::table('borrowings', function (Blueprint $table) {
            $table->string('fine_payment_status', 20)->default('none')->after('fines'); // 'none', 'unpaid', 'paid'
            $table->timestamp('fine_paid_at')->nullable()->after('fine_payment_status');
            $table->foreignId('fine_received_by')->nullable()->after('fine_paid_at')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropForeign(['fine_received_by']);
            $table->dropColumn(['fine_payment_status', 'fine_paid_at', 'fine_received_by']);
        });
    }
};
