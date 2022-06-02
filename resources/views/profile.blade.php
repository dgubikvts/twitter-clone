@extends('layouts.app')

@section('content')
<div class="m-0 p-2 row border-bottom border-light">
     <div class="col-4 p-0 justify-content-center">
          <img src="{{asset($user->profileImage->path)}}" width="150px" height="150px" style="object-fit: cover;" class="img rounded-circle mx-auto d-block">
          <div class="offset-2 mt-3">
               <h5 class="m-0">{{$user->name}}</h5>
               <h6>@<a>{{$user->username}}</a></h6>
          </div>
     </div>
     <form action="{{ route('follow') }}" method="POST" class="col-8 d-flex justify-content-end align-items-end">
          @csrf
          <button type="button" class="above btn username" data-bs-toggle="modal" data-bs-target="#Following">{{$user->following->count()}} Following</button>
          <button type="button" class="above btn username mx-3 amountOfFollowers" data-bs-toggle="modal" data-bs-target="#Followers">{{$user->followers->count()}} Followers</button>
          @if(!Request::is(Auth::user()->username))
          <button type="submit" data-id="{{$user->id}}" class="btn followBtn {{App\Http\Controllers\HomeController::isFollowing($user) ? 'btn-success' : 'btn-info'}} rounded-pill text-white">{{App\Http\Controllers\HomeController::isFollowing($user) ? 'Following' : 'Follow'}}</button>
          @endif
     </form>
</div>
@foreach($user->entities as $tweet)
@if($tweet->post)
<div class="border-bottom border-light m-0 p-2 row position-relative tweet">
     <div class="col-2 p-0 mt-3 d-flex flex-column">
          <a href="/{{$tweet->user->username}}" class="above"><img src="{{asset($tweet->user->profileImage->path)}}" alt="" class="img profile-image rounded-circle mx-auto d-block"></a>
     </div>
     <div class="col-10 p-0 mt-3">
          <div class="d-flex align-items-center">
               <a href="/{{$tweet->user->username}}" class="above username"><h5 class="m-0 me-3 fw-bold">{{$tweet->user->name}}</h5></a>
               <h6 class="m-0 above"><a href="/{{$tweet->user->username}}" class="text-decoration-none atcolor">{{'@'.$tweet->user->username}}</a></h6>
          </div>
          <p class="lead mt-2">{{$tweet->content}}</p>
          @if($tweet->files)
               @foreach($tweet->files as $file)
                    @if($file->type == 'image')
                    <img src="{{asset($file->path)}}" width="100%" class="img rounded mb-2">
                    @elseif($file->type == 'video')
                    <div class="position-relative above">
                         <video src="{{asset($file->path)}}" controls width="100%"></video>
                    </div>
                    @endif
               @endforeach
          @endif
          <form action="{{ route('like') }}" method="POST" class="d-flex justify-content-between">
               @csrf
               <button type="submit" data-id="{{$tweet->id}}" class="above btn rounded-pill me-2 hover submitLike"><i class="fa-heart {{App\Http\Controllers\PostsController::isLiked($tweet) ? 'fa-solid' : 'fa-regular'}} text-danger me-2"></i>{{$tweet->amountOfLikes}}</button>
               <button type="button" class="above btn rounded-pill comment hover"><i class="fa-regular fa-comment comment-icon me-2"></i>{{$tweet->amountOfComments}}</button>
               <button type="button" class="above btn rounded-pill hover"><i class="fa-solid fa-retweet me-2"></i></button>
          </form>
     </div>
     <a href="{{ route('view.tweet', $tweet) }}">
          <span class="linkSpanner"></span>
     </a>
</div>
@endif
@endforeach

<!-- MODALS -->

<div class="modal fade" id="Following" tabindex="-1" aria-labelledby="FollowingLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content">
               <div class="modal-header">
                    <h5 class="modal-title" id="FollowingLabel">Following list</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                    @forelse($user->following as $follow)
                         <div class="d-flex mb-3 align-items-center">
                              <a href="/{{$follow->followee->username}}"><img src="{{asset($follow->followee->profileImage->path)}}" alt="" class="img profile-image rounded-circle me-3"></a>
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



<script>
     $(document).ready(function () {
          $('.comment').hover(function () {
               $(this).children().addClass('fa-solid');
          }, function () {
               $(this).children().removeClass('fa-solid');
          });

          $(document).on("mouseenter", ".linkSpanner", function() {
               $(this).parent().parent().css("background-color", "#F5F8FA");
          });

          $(document).on("mouseleave", ".linkSpanner", function() {
               $(this).parent().parent().css("background-color", "white");
          });


                              
          function updateFollowersModal(allFollowers, user_id, userFollowing){
               var baseUrl = "{{url('/')}}/";
               $('.followersModal').remove();
               modal = document.createElement('div');
               $(modal).attr('class', 'modal fade followersModal');
               $(modal).attr('id', `Followers`);
               $(modal).attr('tabindex', '-1');
               $(modal).attr('aria-hidden', 'true');
               $(modal).append(`
               <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                         <div class="modal-header">
                              <h5 class="modal-title">Followers list</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                         </div>
                         <div class="modal-body">`);
                              if(allFollowers.length > 0){
                                   for(var i = 0; i < allFollowers.length; i++){
                                        $(modal).find('.modal-body').append(`
                                        <div class="d-flex mb-3 align-items-center row${i}">
                                        <a href="/${allFollowers[i].follower.username}"><img src="${baseUrl}${allFollowers[i].follower.profile_image.path}" class="img profile-image rounded-circle me-3"></a>
                                        <div>
                                             <a href="/${allFollowers[i].follower.username}" class="username"><p class="m-0">${allFollowers[i].follower.name}</p></a>
                                             <p class="m-0">@<a href="/${allFollowers[i].follower.username}" class="text-decoration-none text-dark">${allFollowers[i].follower.username}</a></p>
                                        </div>`);
                                        if(allFollowers[i].follower.id != user_id){
                                             $(modal).find(`.row${i}`).append(`
                                             <form action="{{ route('follow') }}" method="POST" class="ms-auto follow-form${i}">
                                                  @csrf`);
                                             if(userFollowing.length == 0)
                                                  $(modal).find(`.follow-form${i}`).append(`<button type="submit" data-id="${allFollowers[i].follower.id}" class="btn followBtn modalBtn btn-info rounded-pill text-white">Follow</button>`);
                                             jQuery.each(userFollowing, function(key, value){
                                                  if(user_id == value.user_id && allFollowers[i].follower.id == value.following){
                                                       $(modal).find(`.follow-form${i}`).append(`
                                                       <button type="submit" data-id="${allFollowers[i].follower.id}" class="btn followBtn modalBtn btn-success rounded-pill text-white">Following</button>`);
                                                  }
                                                  else if(key == userFollowing.length - 1 && $(modal).find(`.follow-form${i}`).find('button[type="submit"]').attr('class') == undefined){
                                                       $(modal).find(`.follow-form${i}`).append(`
                                                       <button type="submit" data-id="${allFollowers[i].follower.id}" class="btn followBtn modalBtn btn-info rounded-pill text-white">Follow</button>`);
                                                  }
                                             });
                                        }
                                   } 
                              }
                              else
                                   $(modal).find('.modal-body').append(`<p class="lead m-0">No one follows this user</p>`);
                              $(modal).find('.modal-content').append(`
                              <div class="modal-footer">
                                   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              </div>`);
               $('body').append(modal);
          }

          var allFollowers = {!! str_replace("'", "\'", json_encode($user->followers)) !!};
          var user_id = "{{Auth::id()}}";
          var userFollowing = {!! str_replace("'", "\'", json_encode(Auth::user()->following)) !!};
          updateFollowersModal(allFollowers, user_id, userFollowing);

          $(document).on('click','.submitLike',function(e){
               e.preventDefault();
               var submitBtn = $(this);
               var entity_id = $(submitBtn).attr('data-id');
               $.ajaxSetup({
                    headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
               });
               $.ajax({
                    type: 'POST',
                    url: "{{ route('like') }}",
                    data: { entity_id: entity_id },
                    success: function (response) {
                         if(response.liked)
                              submitBtn.html('<i class="fa-heart fa-solid text-danger me-2"></i>' + response.likes);
                         else
                              submitBtn.html('<i class="fa-heart fa-regular text-danger me-2"></i>' + response.likes);
                    }
               });
          });

          $(document).on('click','.followBtn',function(e){
               e.preventDefault();
               var followBtn = $(this);
               var isModalBtn = $(followBtn).hasClass('modalBtn');
               var user_id = $(followBtn).attr('data-id');
               $.ajaxSetup({
                    headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
               });
               $.ajax({
                    type: 'POST',
                    url: "{{ route('follow') }}",
                    data: { user_id: user_id },
                    success: function (response) {
                         if(isModalBtn){
                              if(response.following){
                                   $(followBtn).attr('class', 'btn followBtn modalBtn btn-success rounded-pill text-white');
                                   followBtn.html('Following');
                              }
                              else{
                                   $(followBtn).attr('class', 'btn followBtn modalBtn btn-info rounded-pill text-white');
                                   followBtn.html('Follow');
                              }
                         }
                         else{
                              if(response.following){
                                   $(followBtn).attr('class', 'btn followBtn btn-success rounded-pill text-white');
                                   followBtn.html('Following');
                              }
                              else{
                                   $(followBtn).attr('class', 'btn followBtn btn-info rounded-pill text-white');
                                   followBtn.html('Follow');
                              }
                              $('.amountOfFollowers').html(response.followers + ' Followers');
                              updateFollowersModal(response.allFollowers, response.user_id, response.userFollowing);
                         }
                    }
               });
          });
    });

</script>

@endsection
