<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'borrowing_code',
        'borrow_date',
        'return_due_date',
        'actual_return_date',
        'status',
        'renewed_count',
        'notes',
        'admin_note',
        'fines',
        'fine_payment_status',
        'fine_paid_at',
        'fine_received_by',
        'approved_by',
        'returned_to',
    ];

    protected function casts(): array
    {
        return [
            'borrow_date' => 'date',
            'return_due_date' => 'date',
            'actual_return_date' => 'date',
            'fine_paid_at' => 'datetime',
            'fines' => 'decimal:2',
            'renewed_count' => 'integer',
        ];
    }

    /**
     * User who borrowed the book.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Book borrowed.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Admin who approved the borrowing.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Admin who received the return.
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'returned_to');
    }

    /**
     * Admin who collected/processed the fine payment.
     */
    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'fine_received_by');
    }

    /**
     * Review given by user for this borrowing.
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Check if currently overdue.
     */
    public function isOverdue(): bool
    {
        if (in_array($this->status, ['borrowed', 'approved']) && $this->return_due_date < Carbon::today()) {
            return true;
        }

        return $this->status === 'overdue';
    }

    /**
     * Days overdue calculation.
     */
    public function daysOverdue(): int
    {
        $compareDate = $this->actual_return_date ?? Carbon::today();
        if ($compareDate > $this->return_due_date) {
            return $this->return_due_date->diffInDays($compareDate);
        }

        return 0;
    }
}
