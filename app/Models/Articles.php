<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articles extends Model
{
    protected $fillable = ['title', 'alias', 'thumbnail','link', 'content', 'excerpt', 'categories_id', 'published'];
}
