<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'following',
    ];

    public function follower(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function followee(){
        return $this->belongsTo(User::class, 'following');
    }

    public function addFollow($user_id, $following){
        $this->user_id = $user_id;
        $this->following = $following;
        $this->save();
    }

    public function removeFollow(){
        return $this->delete();
    }
}
