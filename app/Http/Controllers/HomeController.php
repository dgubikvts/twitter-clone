<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Posts;
use App\Models\Entity;
use App\Models\Follow;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $following = Follow::where('user_id', Auth::id())->pluck('following');
        $following->put($following->count(), Auth::id());
        $entities = Entity::whereIn('user_id', $following->all())->orderByDesc('created_at')->get();
        return view('home')->with('entities', $entities);
    }

    public function profile($username){
        $user = User::where('username', $username)->with('followers.follower.profileImage')->firstOrFail();
        return view('profile')->with('user', $user);
    }

    public function follow(Request $request){
        $follow = Follow::where([['user_id', Auth::id()], ['following', $request->user_id]])->first();
        $user = User::where('id', $request->user_id)->first();
        if($follow){
            $follow->removeFollow();
        }
        else{
            $follow = new Follow();
            $follow->addFollow(Auth::id(), $request->user_id);
        }
        $allFollowers = Follow::where('following', $request->user_id)->with('follower.profileImage')->get();
        $userFollowing = Follow::all();
        $following = $this->isFollowing($user);
        $followers = $user->followers->count();
        return response()->json(['following' => $following, 'followers' => $followers, 'allFollowers' => $allFollowers, 'user_id' => Auth::id(), 'userFollowing' => $userFollowing]);
    }

    public static function isFollowing(User $user){
        $follow = Follow::where([['user_id', Auth::id()], ['following', $user->id]])->count();
        return $follow;
    }
}
