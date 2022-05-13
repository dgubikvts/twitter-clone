<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Entity;
use App\Models\EntityFile;
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
            'content' => 'required',
            'files.*' => 'mimetypes:image/gif,image/jpeg,image/png,video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi|max:20000'
        ]);
        $entity = new Entity;
        $entity->save();
        $tweet = new Posts;
        $tweet->entity_id = $entity->id;
        $entity->content = $request->input('content');
        $entity->user_id = Auth::id();
        $tweet->save();
        $entity->save();
        if($request->has('files')){
            foreach($request->file('files') as $file){
                $file_name = $file->getClientOriginalName();
                $file_size = $file->getSize();
                $file_extension = $file->getClientOriginalExtension();
                if($file_extension == 'png' || $file_extension == 'jpg' || $file_extension == 'jpeg' || $file_extension == 'gif') $file_type = 'image';
                else $file_type = 'video';
                $path = 'storage/files/' . $entity->id . '/' . $file_name;
                $file->storeAs('public/files/' . $entity->id, $file_name);
                $new_file = new EntityFile();
                $new_file->create($entity->id, $file_name, $file_size, $path, $file_type);
            }
        }
        return response()->json();
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

    public function comment(Request $request){
        $request->validate([
            'comment' => 'required',
            'files.*' => 'mimetypes:image/gif,image/jpeg,image/png,video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi|max:20000'
        ]);
        $entity = Entity::where('id', $request->entity_id)->first();
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
        $new_entity->content = $request->input('comment');
        $new_entity->user_id = Auth::id();
        $entity->save();
        $new_entity->save();
        if($request->has('files')){
            foreach($request->file('files') as $file){
                $file_name = $file->getClientOriginalName();
                $file_size = $file->getSize();
                $file_extension = $file->getClientOriginalExtension();
                if($file_extension == 'png' || $file_extension == 'jpg' || $file_extension == 'jpeg' || $file_extension == 'gif') $file_type = 'image';
                else $file_type = 'video';
                $path = 'storage/files/' . $new_entity->id . '/' . $file_name;
                $file->storeAs('public/files/' . $new_entity->id, $file_name);
                $new_file = new EntityFile();
                $new_file->create($new_entity->id, $file_name, $file_size, $path, $file_type);
            }
        }
        return response()->json();
    }

    public static function isLiked(Entity $entity){
        $like = Like::where([['user_id', Auth::id()], ['entity_id', $entity->id]])->count();
        return $like;
    }


}
