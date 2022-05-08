<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    use HasFactory;

    public function comments(){
        return $this->hasMany(Comment::class, 'post_id')->orderByDesc('created_at');
    }

    public function entity(){
        return $this->belongsTo(Entity::class);
    }
}
