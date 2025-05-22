<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'summary',
        'pages',
        'published_at',
        'cover',
        'category_id',
        'status',
    ];

    protected $dates = ['published_at'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
    public function getHasUserReviewAttribute()
    {
        if (!auth()->check()) {
            return false;
        }
        
        return $this->reviews->contains('user_id', auth()->id());
    }
}