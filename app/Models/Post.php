<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
    protected $primaryKey = 'id';

    protected $fillable = [
        'heading_image',
        'title',
        'slug',
        'content',
        'date',
    ];
    protected $hidden = ['created_at', 'updated_at'];

    public function getHeadingImageRef()
    {
        return $this->attributes['heading_image'];
    }

    public function getHeadingImageAttribute()
    {
        return $this->attributes['heading_image'] ? Storage::url($this->attributes['heading_image']) : null;
    }
}