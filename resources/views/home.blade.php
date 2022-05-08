@extends('layouts.app')

@section('content')
<div class="container">
     <div class="row flex-wrap">
       <div class="col-3">
          <h3 class="mb-4"><a href="/home" class="text-decoration-none text-dark">Home</a></h3>
          <h3 class="mb-4"><a href="" class="text-decoration-none text-dark">Messages</a></h3>
          <h3 class="mb-4"><a href="{{ route('profile', Auth::user()->username) }}" class="text-decoration-none text-dark">Profile</a></h3>
          <h3 class="mb-4"><a class="text-decoration-none text-dark" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a></h3>
       </div>
       <div class="col-5 border-top border-start border-end border-light p-0">
          <form action="{{ route('tweet') }}" method="POST" class="d-flex mt-3 p-2 border-bottom border-light">
               @csrf
               <div class="col-2">
                    <a href="/{{Auth::user()->username}}"><img src="https://via.placeholder.com/50" alt="" class="rounded-circle mx-auto d-block"></a>
               </div>
               <div class="col-10">
                    <textarea name="content" class="col-12 border-0 form-control textarea mb-2" maxlength="280" rows="3" placeholder="What's happening?" required></textarea>
                    <button type="submit" class="btn dugme rounded-pill float-end text-white me-2">Tweet</button>
               </div>
          </form>        
          @forelse($entities as $entity)
          @if($entity->post)
          <div class="border-bottom border-light m-0 p-2 row position-relative tweet">
               <div class="col-2 p-0 mt-3 above">
                    <a href="/{{$entity->user->username}}"><img src="https://via.placeholder.com/50" alt="" class="rounded-circle mx-auto d-block"></a>
               </div>
               <div class="col-10 p-0 mt-3">
                    <div class="d-flex align-items-center">
                         <a href="/{{$entity->user->username}}" class="above username"><h5 class="m-0 me-3 fw-bold">{{$entity->user->name}}</h5></a>
                         <h6 class="m-0 above"><a href="/{{$entity->user->username}}" class="text-decoration-none atcolor">{{'@'.$entity->user->username}}</a></h6>
                    </div>
                    <p class="lead mt-2">{{$entity->content}}</p>
                    <form action="{{ route('like', $entity) }}" method="POST" class="d-flex justify-content-between">
                         @csrf
                         <button type="submit" class="above btn rounded-pill me-2 hover"><i class="fa-heart {{App\Http\Controllers\PostsController::isLiked($entity) ? 'fa-solid' : 'fa-regular'}} text-danger me-2"></i>{{$entity->amountOfLikes}}</button>
                         <button type="button" class="above btn rounded-pill comment hover"><i class="fa-regular fa-comment comment-icon me-2"></i>{{$entity->amountOfComments}}</button>
                         <button type="button" class="above btn rounded-pill hover"><i class="fa-solid fa-retweet me-2"></i></button>
                    </form>
               </div>
               <a href="{{ route('view.tweet', $entity) }}">
                    <span class="linkSpanner"></span>
               </a>
          </div>
          @endif
          @empty
          @endforelse
          
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
