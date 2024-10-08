<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'publish',
        'description',
        'media_id',
    ];

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function scopeEntities($query, $entities)
    {
        return Helper::entities($query, $entities);
    }
}
