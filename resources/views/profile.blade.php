@extends('layouts.app')

@section('content')
<div class="container">
     <div class="row">
          <div class="col-3">
               <h3 class="mb-4"><a href="/home" class="text-decoration-none text-dark">Home</a></h3>
               <h3 class="mb-4"><a href="" class="text-decoration-none text-dark">Messages</a></h3>
               <h3 class="mb-4"><a href="{{ route('profile', Auth::user()->username) }}" class="text-decoration-none text-dark">Profile</a></h3>
               <h3 class="mb-4"><a class="text-decoration-none text-dark" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a></h3>
          </div>
          <div class="col-5 border p-0">
               <div class="border border-light m-0 p-2 row">
                    <div class="col-4 p-0 justify-content-center">
                         <img src="https://via.placeholder.com/150" alt="" class="rounded-circle mx-auto d-block">
                         <div class="offset-2 mt-3">
                              <h5 class="m-0">{{$user->name}}</h5>
                              <h6>@<a>{{$user->username}}</a></h6>
                         </div>
                    </div>
                    <form action="{{ route('follow', $user) }}" method="POST" class="col-8 d-flex justify-content-end align-items-end">
                         @csrf
                         <button type="button" class="above btn username" data-bs-toggle="modal" data-bs-target="#Following{{$user->id}}">{{$user->following->count()}} Following</button>
                         <button type="button" class="above btn username mx-3" data-bs-toggle="modal" data-bs-target="#Followers{{$user->id}}">{{$user->followers->count()}} Followers</button>
                         @if(!Request::is(Auth::user()->username))
                         <button type="submit" class="btn {{App\Http\Controllers\HomeController::isFollowing($user) ? 'btn-success' : 'btn-info'}} rounded-pill text-white">{{App\Http\Controllers\HomeController::isFollowing($user) ? 'Following' : 'Follow'}}</button>
                         @endif
                    </form>
               </div>
               @foreach($user->entities as $tweet)
               @if($tweet->post)
               <div class="border-bottom border-light m-0 p-2 row position-relative tweet">
                    <div class="col-2 p-0 mt-3 above">
                         <a href="/{{$tweet->user->username}}"><img src="https://via.placeholder.com/50" alt="" class="rounded-circle mx-auto d-block"></a>
                    </div>
                    <div class="col-10 p-0 mt-3">
                         <div class="d-flex align-items-center">
                              <a href="/{{$tweet->user->username}}" class="above username"><h5 class="m-0 me-3 fw-bold">{{$tweet->user->name}}</h5></a>
                              <h6 class="m-0 above"><a href="/{{$tweet->user->username}}" class="text-decoration-none atcolor">{{'@'.$tweet->user->username}}</a></h6>
                         </div>
                         <p class="lead mt-2">{{$tweet->content}}</p>
                         <form action="{{ route('like', $tweet) }}" method="POST" class="d-flex justify-content-between">
                              @csrf
                              <button type="submit" class="above btn rounded-pill me-2 hover"><i class="fa-heart {{App\Http\Controllers\PostsController::isLiked($tweet) ? 'fa-solid' : 'fa-regular'}} text-danger me-2"></i>{{$tweet->amountOfLikes}}</button>
                              <button type="button" class="above btn rounded-pill `comment` hover"><i class="fa-regular fa-comment comment-icon me-2"></i>{{$tweet->amountOfComments}}</button>
                              <button type="button" class="above btn rounded-pill hover"><i class="fa-solid fa-retweet me-2"></i></button>
                         </form>
                    </div>
                    <a href="{{ route('view.tweet', $tweet) }}">
                         <span class="linkSpanner"></span>
                    </a>
               </div>
               @endif
               @endforeach
          </div>
     <div class="col-4">
          <form action="" method="GET">
          <div class="input-group mb-3">
               <input type="text" class="form-control border-info" placeholder="Search..." >
               <div class="btn btn-outline-info"><i class="fa-solid fa-magnifying-glass"></i></div>
          </div>
          </form>
     </div>
</div>



<!-- MODALS -->

<div class="modal fade" id="Following{{$user->id}}" tabindex="-1" aria-labelledby="FollowingLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content">
               <div class="modal-header">
                    <h5 class="modal-title" id="FollowingLabel">Following list</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                    @forelse($user->following as $follow)
                         <div class="d-flex mb-3 align-items-center">
                              <a href="/{{$follow->followee->username}}"><img src="https://via.placeholder.com/50" alt="" class="rounded-circle me-3"></a>
                              <div>
                                   <a href="/{{$follow->followee->username}}" class="username"><p class="m-0">{{$follow->followee->name}}</p></a>
                                   <p class="m-0">@<a href="/{{$follow->followee->username}}" class="text-decoration-none text-dark">{{$follow->followee->username}}</a></p>
                              </div>
                              @if($follow->followee->username !== Auth::user()->username)
                              <form action="{{ route('follow', $follow->followee) }}" method="POST" class="ms-auto">
                                   @csrf
                                   <button type="submit" class="btn {{App\Http\Controllers\HomeController::isFollowing($follow->followee) ? 'btn-success' : 'btn-info'}} rounded-pill text-white">{{App\Http\Controllers\HomeController::isFollowing($follow->followee) ? 'Following' : 'Follow'}}</button>
                              </form>
                              @endif
                         </div>
                    @empty
                         <p class="lead m-0">This person follows 0 people</p>
                    @endforelse
               </div>
               <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
               </div>
          </div>
     </div>
</div>

<div class="modal fade" id="Followers{{$user->id}}" tabindex="-1" aria-labelledby="FollowersLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
               <div class="modal-header">
                    <h5 class="modal-title" id="FollowersLabel">Followers list</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                    @forelse($user->followers as $follow)
                         <div class="d-flex mb-3 align-items-center">
                              <a href="/{{$follow->follower->username}}"><img src="https://via.placeholder.com/50" alt="" class="rounded-circle me-3"></a>
                              <div>
                                   <a href="/{{$follow->follower->username}}" class="username"><p class="m-0">{{$follow->follower->name}}</p></a>
                                   <p class="m-0">@<a href="/{{$follow->follower->username}}" class="text-decoration-none text-dark">{{$follow->follower->username}}</a></p>
                              </div>
                              @if($follow->follower->username !== Auth::user()->username)
                              <form action="{{ route('follow', $follow->follower) }}" method="POST" class="ms-auto">
                                   @csrf
                                   <button type="submit" class="btn {{App\Http\Controllers\HomeController::isFollowing($follow->follower) ? 'btn-success' : 'btn-info'}} rounded-pill text-white">{{App\Http\Controllers\HomeController::isFollowing($follow->follower) ? 'Following' : 'Follow'}}</button>
                              </form>
                              @endif
                         </div>
                    @empty
                         <p class="lead m-0">No one follows this user</p>
                    @endforelse
               </div>
               <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
               </div>
          </div>
     </div>
</div>
<!-- MODALS -->

<script>
     $(document).ready(function () {
        $('.comment').hover(function () {
            $(this).children().addClass('fa-solid');
        }, function () {
          $(this).children().removeClass('fa-solid');
        });
        $('.linkSpanner').hover(function(){
          $(this).parent().parent().css("background-color", "#F5F8FA");
        },
          function(){
          $(this).parent().parent().css("background-color", "white");
          });
    });
</script>

@endsection
