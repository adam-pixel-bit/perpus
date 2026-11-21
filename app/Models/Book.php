<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'isbn',
        'author',
        'publisher',
        'publication_year',
        'stock',
        'available_stock',
        'shelf_location',
        'description',
        'cover_image',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'publication_year' => 'integer',
            'stock' => 'integer',
            'available_stock' => 'integer',
            'is_available' => 'boolean',
        ];
    }

    /**
     * Category relation.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Borrowings records for this book.
     */
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Get the URL for the cover image or a default fallback.
     */
    public function getCoverUrlAttribute(): string
    {
        if ($this->cover_image) {
            if (str_starts_with($this->cover_image, 'http://') || str_starts_with($this->cover_image, 'https://')) {
                return $this->cover_image;
            }

            return asset('storage/'.$this->cover_image);
        }

        return 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=600&q=80';
    }
}
