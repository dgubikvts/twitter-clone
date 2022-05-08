<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Entity;
use App\Models\Posts;
use App\Models\Like;
use App\Models\Comment;
use App\Models\User;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function tweet(Request $request){
        $request->validate([
            'content' => 'required'
        ]);
        $entity = new Entity;
        $entity->save();
        $tweet = new Posts;
        $tweet->entity_id = $entity->id;
        $entity->content = $request->input('content');
        $entity->user_id = Auth::id();
        $tweet->save();
        $entity->save();
        return redirect('/home');
    }

    public function viewTweet(Entity $entity){
        $tweetCollection = collect();
        $i = $entity;
        while($i->comment){
            $tweetCollection->put($tweetCollection->count(), Entity::where('id', $i->comment->commented_on)->first());
            $i = $tweetCollection[$tweetCollection->count() - 1];
        }
        $sorted = $tweetCollection->sortBy(['created_at', 'desc']);
        return view('tweet')->with('entity', $entity)->with('aboveTweets', $sorted);
    }

    public function like(Entity $entity){
        $like = Like::where([['user_id', Auth::id()], ['entity_id', $entity->id]])->first();
        if($like){
            $like->removeLike();
            $entity->amountOfLikes--;
        }
        else{
            $like = new Like();
            $like->addLike(Auth::id(), $entity->id);
            $entity->amountOfLikes++;
        }
        $entity->save();
        return redirect()->back();
    }


    public static function isLiked(Entity $entity){
        $like = Like::where([['user_id', Auth::id()], ['entity_id', $entity->id]])->count();
        return $like;
    }

    public function comment(Entity $entity, Request $request){
        $new_entity = new Entity;
        $new_entity->save();
        $comment = new Comment();
        $comment->addComment($new_entity->id, $entity->id);
        $entity->amountOfComments++;
        $i = $entity;
        while($i->comment){
            $i = Entity::where('id', $i->comment->commented_on)->first();
            $i->amountOfComments++;
            $i->save();
            
        }
        $new_entity->content = $request->comment;
        $new_entity->user_id = Auth::id();
        $entity->save();
        $new_entity->save();
        return redirect()->back();
    }
}
