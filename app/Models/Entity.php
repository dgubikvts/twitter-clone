<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'amountOfLikes',
        'amountOfComments',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function post(){
        return $this->hasOne(Posts::class);
    }

    public function comment(){
        return $this->hasOne(Comment::class);
    }

    public function comments_on_me(){
        return $this->hasMany(Comment::class, 'commented_on');
    }

    public function likes(){
        return $this->hasMany(Like::class);
    }

    public function files(){
        return $this->hasMany(EntityFile::class);
    }
}
