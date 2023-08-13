<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'admin_id',
        'image',
        'content',
    ];

    /**
     * The attributes that should be datetime.
     *
     */
    protected $dates = ['deleted_at'];

    /**
     * image
     *
     * @return Attribute
     */
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => asset('/storage/news/' . $image),
        );
    }

    public function comment()
    {
        return $this->hasMany(Comment::class, 'news_id');
    }
}
