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
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('book_id')->constrained('books')->restrictOnDelete();
            $table->string('borrowing_code', 30)->unique();
            $table->date('borrow_date');
            $table->date('return_due_date');
            $table->date('actual_return_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'borrowed', 'returned', 'overdue'])->default('pending');
            $table->text('notes')->nullable();
            $table->text('admin_note')->nullable();
            $table->decimal('fines', 10, 2)->default(0);
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('returned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('borrow_date');
            $table->index('return_due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
