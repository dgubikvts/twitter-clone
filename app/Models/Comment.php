<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'entity_id',
        'commented_on',
    ];

    public function entity(){
        return $this->belongsTo(Entity::class);
    }

    public function commented_on(){
        return $this->belongsTo(Entity::class, 'entity_id');
    }

    public function addComment($entity_id, $commented_on){
        $this->entity_id = $entity_id;
        $this->commented_on = $commented_on;
        $this->save();
    }

}
