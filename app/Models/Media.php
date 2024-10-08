<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
    protected $table = 'medias';

    public function movie()
    {
        return $this->hasOne(Movie::class, 'media_id', 'id');
    }
}
