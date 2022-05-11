<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntityFile extends Model
{
    use HasFactory;

    public function entity(){
        return $this->belongsTo(Entity::class);
    }
    
    public function create($entity_id, $name, $size, $path, $type){
        $this->entity_id = $entity_id;
        $this->name = $name;
        $this->size = $size;
        $this->path = $path;
        $this->type = $type;
        $this->save();
    }
}
