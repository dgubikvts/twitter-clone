<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileImage extends Model
{
    use HasFactory;

    public function user(){
        $this->belongsTo(User::class);
    }

    public function create($user_id, $name, $size, $path){
        $this->user_id = $user_id;
        $this->name = $name;
        $this->size = $size;
        $this->path = $path;
        $this->save();
    }
}
