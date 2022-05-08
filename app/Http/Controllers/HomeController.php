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
        $user = User::where('username', $username)->firstOrFail();
        return view('profile')->with('user', $user);
    }

    public function follow(User $user){
        $follow = Follow::where([['user_id', Auth::id()], ['following', $user->id]])->first();
        if($follow){
            $follow->removeFollow();
        }
        else{
            $follow = new Follow();
            $follow->addFollow(Auth::id(), $user->id);
        }
        return redirect()->back();
    }

    public static function isFollowing(User $user){
        $follow = Follow::where([['user_id', Auth::id()], ['following', $user->id]])->count();
        return $follow;
    }
}
