<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'comment',
        'news_id',
        'user_id',
    ];

    /**
     * The attributes that should be datetime.
     *
     */
    protected $dates = ['deleted_at'];

    public function comment()
    {
        return $this->belongsTo(News::class, 'news_id');
    }
}
