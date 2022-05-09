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
          <div class="col-5 border border-light p-0">
               <div class="border border-light m-0 row position-relative">
                    @forelse($aboveTweets as $aboveTweet)
                    <div class="m-0 p-2 row position-relative">
                         <div class="col-2 p-0 mt-3 above">
                              <a href="/{{$aboveTweet->user->username}}"><img src="https://via.placeholder.com/50" alt="" class="rounded-circle"></a>
                              <div class="d-flex justify-content-around">
                                   <div class="d-flex flex-column">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                   </div>
                                   <div class=""></div>
                              </div>
                         </div>
                         <div class="col-10 p-0 mt-3">
                              <div class="d-flex align-items-center">
                                   <a href="/{{$aboveTweet->user->username}}" class="above username"><h5 class="m-0 me-3 fw-bold">{{$aboveTweet->user->name}}</h5></a>
                                   <h6 class="m-0 above"><a href="/{{$aboveTweet->user->username}}" class="text-decoration-none text-dark">{{'@'.$aboveTweet->user->username}}</a></h6>
                              </div>
                              @if($aboveTweet->comment)
                                   <div class="d-flex">
                                        <h6 class="above">Replying to <a href="/{{$aboveTweets[$loop->index - 1]->user->username}}" class="text-decoration-none twitter-color">{{'@'.$aboveTweets[$loop->index - 1]->user->username}}</a></h6>
                                   </div>
                              @endif
                              <p class="lead mt-2">{{$aboveTweet->content}}</p>
                              <form action="{{ route('like', $aboveTweet) }}" method="POST" class="d-flex justify-content-between">
                                   @csrf
                                   <button type="submit" class="above btn rounded-pill me-2 hover"><i class="fa-heart {{App\Http\Controllers\PostsController::isLiked($aboveTweet) ? 'fa-solid' : 'fa-regular'}} text-danger me-2"></i>{{$aboveTweet->amountOfLikes}}</button>
                                   <button type="button" class="above btn rounded-pill comment hover"><i class="fa-regular fa-comment comment-icon me-2"></i>{{$aboveTweet->amountOfComments}}</button>
                                   <button type="button" class="above btn rounded-pill hover"><i class="fa-solid fa-retweet me-2"></i></button>
                              </form>
                         </div>
                         <a href="{{ route('view.tweet', $aboveTweet) }}">
                              <span class="linkSpanner"></span>
                         </a>
                    </div>
                    @empty
                    @endforelse
                    <div class="d-flex mt-2">
                         <div class="col-2">
                              <a href="/{{$entity->user->username}}"><img src="https://via.placeholder.com/50" alt="" class="rounded-circle"></a>
                         </div>
                         <div class="d-flex justify-content-center flex-column">
                              <a href="/{{$entity->user->username}}" class="username"><h5 class="m-0 me-3 fw-bold">{{$entity->user->name}}</h5></a>
                              <h6 class="m-0"><a href="/{{$entity->username}}" class="text-decoration-none text-dark">{{'@'.$entity->user->username}}</a></h6>
                         </div>
                    </div>
                    <div class="mt-3">
                         @if($entity->comment)
                         <div class="d-flex">
                              <h6 class="above">Replying to <a href="/{{$aboveTweets[$aboveTweets->count()-1]->user->username}}" class="text-decoration-none twitter-color">{{'@'.$aboveTweets[$aboveTweets->count()-1]->user->username}}</a></h6>
                         </div>
                         @endif
                         <p class="lead mt-2">{{$entity->content}}</p>
                         <p class="py-3 m-0 border-bottom">Tweeted on: {{$entity->created_at}}</p>
                         <div class="d-flex justify-content-between align-items-center">
                              <button type="button" class="btn px-0" data-bs-toggle="modal" data-bs-target="#Likes{{$entity->id}}">{{$entity->amountOfLikes}} likes</button>
                              <p class="m-0 px-0">{{$entity->amountOfComments}} comments</p>
                              <p class="m-0 px-0">x retweets</p>
                         </div>
                         <form action="{{ route('like', $entity) }}" method="POST" class="d-flex justify-content-between border-top border-bottom">
                              @csrf
                              <button type="submit" class="btn rounded-pill hover"><i class="fa-heart {{App\Http\Controllers\PostsController::isLiked($entity) ? 'fa-solid' : 'fa-regular'}} text-danger"></i></button>
                              <button type="button" class="btn rounded-pill comment hover"><i class="fa-regular fa-comment me-2"></i>{{$entity->amountOfComments}}</button>
                              <button type="button" class="btn rounded-pill hover"><i class="fa-solid fa-retweet me-2"></i></button>
                         </form>
                    </div>
                    <form action="{{ route('comment', $entity) }}" method="POST" class="d-flex my-3 align-items-center row">
                         @csrf
                         <div class="col-2">
                              <a href="/{{Auth::user()->username}}"><img src="https://via.placeholder.com/50" alt="" class="rounded-circle"></a>
                         </div>
                         <div class="col-10 d-flex">
                              <input type="text" name="comment" class="form-control me-2 border-0" maxlength="280" placeholder="Tweet your reply" style="background-color: white;" required>
                              <button class="btn rounded-pill btn-outline-info dugme" type="submit">Reply</button>
                         </div>
                    </form>
                    @foreach($entity->comments_on_me as $comment)
                    <div class="border-top border-light m-0 row position-relative tweet">
                         <div class="col-2 p-0 mt-3 above">
                              <a href="/{{$comment->entity->user->username}}"><img src="https://via.placeholder.com/50" alt="" class="rounded-circle"></a>
                         </div>
                         <div class="col-10 p-0 mt-3">
                              <div class="d-flex align-items-center">
                                   <a href="/{{$comment->entity->user->username}}" class="above username"><h5 class="m-0 me-3 fw-bold">{{$comment->entity->user->name}}</h5></a>
                                   <h6 class="m-0 above"><a href="/{{$comment->entity->user->username}}" class="text-decoration-none text-dark">{{'@'.$comment->entity->user->username}}</a></h6>
                              </div>
                              <div class="d-flex">
                                   <h6 class="above">Replying to <a href="/{{$entity->user->username}}" class="text-decoration-none twitter-color">{{'@'.$entity->user->username}}</a></h6>
                              </div>
                              <p class="lead">{{$comment->entity->content}}</p>
                              <form action="{{ route('like', $comment->entity) }}" method="POST" class="d-flex justify-content-between">
                                   @csrf
                                   <button type="submit" class="above btn rounded-pill me-2 hover"><i class="fa-heart {{App\Http\Controllers\PostsController::isLiked($comment->entity) ? 'fa-solid' : 'fa-regular'}} text-danger me-2"></i>{{$comment->entity->amountOfLikes}}</button>
                                   <button type="button" class="above btn rounded-pill comment hover"><i class="fa-regular fa-comment comment-icon me-2"></i>{{$comment->entity->amountOfComments}}</button>
                                   <button type="button" class="above btn rounded-pill hover"><i class="fa-solid fa-retweet me-2"></i></button>
                              </form>
                         </div>
                         <a href="{{ route('view.tweet', $comment->entity) }}">
                              <span class="linkSpanner"></span>
                         </a>
                    </div>
                    @endforeach
               </div>
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
</div>

<!-- MODALS -->

<!-- likes -->
<div class="modal fade" id="Likes{{$entity->id}}" tabindex="-1" aria-labelledby="LikesLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
               <div class="modal-header">
                    <h5 class="modal-title" id="LikesLabel">Likes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                    @forelse($entity->likes as $like)
                         <div class="d-flex mb-3 align-items-center position-relative">
                              <img src="https://via.placeholder.com/50" alt="" class="rounded-circle me-3">
                              <div>
                                   <p class="m-0">{{$like->user->name}}</p>
                                   <p class="m-0">@<a href="/{{$like->user->username}}" class="text-decoration-none text-dark">{{$like->user->username}}</a></p>
                              </div>
                              @if($like->user->username !== Auth::user()->username)
                              <form action="{{ route('follow', $like->user) }}" method="POST" class="ms-auto above">
                                   @csrf
                                   <button type="submit" class=" btn {{App\Http\Controllers\HomeController::isFollowing($like->user) ? 'btn-success' : 'btn-info'}} rounded-pill text-white">{{App\Http\Controllers\HomeController::isFollowing($like->user) ? 'Following' : 'Follow'}}</button>
                              </form>
                              @endif
                              <a href="/{{$like->user->username}}">
                                   <span class="linkSpanner"></span>
                              </a>
                         </div>
                    @empty
                         <p class="lead m-0">This post has 0 likes</p>

                    @endforelse
               </div>
               <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
               </div>
          </div>
     </div>
</div>
<!-- likes -->

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
