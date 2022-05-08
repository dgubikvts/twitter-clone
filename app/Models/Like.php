<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function entity(){
        return $this->belongsTo(Entity::class);
    }

    public function addLike($user_id, $entity_id){
        $this->user_id = $user_id;
        $this->entity_id = $entity_id;
        $this->save();
    }

    public function removeLike(){
        return $this->delete();
    }
}
