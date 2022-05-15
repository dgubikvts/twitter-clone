@extends('layouts.app')

@section('content')

<div class="m-0 row position-relative">
     @forelse($aboveTweets as $aboveTweet)
     <div class="m-0 p-2 row position-relative">
          <div class="col-2 p-0 mt-3 d-flex flex-column">
               <a href="/{{$aboveTweet->user->username}}" class="above"><img src="{{asset($aboveTweet->user->profileImage->path)}}" alt="" class="img profile-image rounded-circle"></a>
               <div class="vr h-100 ms-4"></div>
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
               @if($aboveTweet->files)
                    <div class="d-flex flex-wrap">
                    @foreach($aboveTweet->files as $file)
                    <div class="{{$aboveTweet->files->count() % 2 !== 0 && $loop->last ? 'w-100' : 'w-50'}} pe-1">
                         @if($file->type == 'image')
                         <img src="{{asset($file->path)}}" class="img rounded mb-2 {{$aboveTweet->files->count() == 1 ? 'w-100' : 'img-grid' }}">
                         @elseif($file->type == 'video')
                         <div class="position-relative above">
                              <video src="{{asset($file->path)}}" class="{{$aboveTweet->files->count() == 1 ? 'w-100' : 'img-grid'}}" controls></video>
                         </div>
                         @endif
                    </div>
                    @endforeach
                    </div>
               @endif             
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
     <div class="d-flex mt-2 p-0">
          <div class="col-2 p-2">
               <a href="/{{$entity->user->username}}"><img src="{{asset($entity->user->profileImage->path)}}" alt="" class="img profile-image rounded-circle"></a>
          </div>
          <div class="d-flex justify-content-center flex-column">
               <a href="/{{$entity->user->username}}" class="username"><h5 class="m-0 me-3 fw-bold">{{$entity->user->name}}</h5></a>
               <h6 class="m-0"><a href="/{{$entity->user->username}}" class="text-decoration-none text-dark">{{'@'.$entity->user->username}}</a></h6>
          </div>
     </div>
     <div class="mt-3">
          @if($entity->comment)
          <div class="d-flex">
               <h6 class="above">Replying to <a href="/{{$aboveTweets[$aboveTweets->count()-1]->user->username}}" class="text-decoration-none twitter-color">{{'@'.$aboveTweets[$aboveTweets->count()-1]->user->username}}</a></h6>
          </div>
          @endif
          <p class="lead mt-2">{{$entity->content}}</p>
          @if($entity->files)
               @foreach($entity->files as $file)
                    @if($file->type == 'image')
                    <img src="{{asset($file->path)}}" width="100%" class="img rounded mb-2">
                    @elseif($file->type == 'video')
                    <div class="position-relative above">
                         <video src="{{asset($file->path)}}" controls width="100%"></video>
                    </div>
                    @endif
               @endforeach
          @endif
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
     <form action="{{ route('comment') }}" method="POST" class="d-flex my-3 align-items-center row" id="upload-files" enctype="multipart/form-data" data-id="{{$entity->id}}">
          @csrf
          <div class="col-2 align-self-start p-0">
               <a href="/{{Auth::user()->username}}" class="p-2"><img src="{{asset(Auth::user()->profileImage->path)}}" alt="" class="img profile-image rounded-circle"></a>
          </div>
          <div class="col-10 d-flex flex-column">
               <textarea name="comment" class="form-control me-2 border-0 textarea" maxlength="280" rows="2" placeholder="Tweet your reply" required></textarea>
               <div class="d-flex flex-wrap images-wrap"></div>
               <div class="d-flex align-items-center justify-content-between mt-2">
                    <div class="file-upload">
                         <input type="file" name="files[]" id="file" accept="image/*, video/*" onchange="loadFile(this)" multiple><label for="file"><i class="fa-regular fa-image me-2 btn rounded-pill upload-file twitter-color"></i></label>
                    </div>
                    <button type="submit" class="btn rounded-pill btn-outline-info dugme">Reply</button>
               </div>
          </div>
     </form>
     @foreach($entity->comments_on_me as $comment)
     <div class="border-top border-light m-0 row position-relative tweet">
          <div class="col-2 p-0 mt-3 d-flex flex-column">
               <a href="/{{$comment->entity->user->username}}" class="above"><img src="{{asset($comment->entity->user->profileImage->path)}}" alt="" class="img profile-image rounded-circle"></a>
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
               @if($comment->entity->files)
                    <div class="d-flex flex-wrap">
                    @foreach($comment->entity->files as $file)
                    <div class="{{$comment->entity->files->count() % 2 !== 0 && $loop->last ? 'w-100' : 'w-50'}} pe-1">
                         @if($file->type == 'image')
                         <img src="{{asset($file->path)}}" class="img rounded mb-2 {{$comment->entity->files->count() == 1 ? 'w-100' : 'img-grid' }}">
                         @elseif($file->type == 'video')
                         <div class="position-relative above">
                              <video src="{{asset($file->path)}}" class="{{$comment->entity->files->count() == 1 ? 'w-100' : 'img-grid'}}" controls></video>
                         </div>
                         @endif
                    </div>
                    @endforeach
                    </div>
               @endif
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
                              <img src="{{asset($like->user->profileImage->path)}}" alt="" class="img profile-image rounded-circle me-3">
                              <div>
                                   <p class="m-0">{{$like->user->name}}</p>
                                   <p class="m-0">@<a href="/{{$like->user->username}}" class="text-decoration-none text-dark">{{$like->user->username}}</a></p>
                              </div>
                              @if($like->user->username !== Auth::user()->username)
                              <form action="{{ route('follow', $like->user) }}" method="POST" class="ms-auto above">
                                   @csrf
                                   <button type="submit" class="btn {{App\Http\Controllers\HomeController::isFollowing($like->user) ? 'btn-success' : 'btn-info'}} rounded-pill text-white">{{App\Http\Controllers\HomeController::isFollowing($like->user) ? 'Following' : 'Follow'}}</button>
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
     var fileList = [];
     var fileInput = document.getElementById('file');
     var dataTransfer = new DataTransfer();

     function loadFile(fileInput){
          var imageWrap = document.getElementsByClassName('images-wrap')[0];
          var images = ['image/jpg', 'image/png', 'image/jpeg', 'image/gif'];
          var count = 0;
          for(var i = 0; i < fileInput.files.length; i++){
               fileList.push(fileInput.files[i]);
               if(images.includes(fileInput.files[i].type)){
                    imageWrap.innerHTML += 
                    `<div class="position-relative p-2 div${fileList.length - 1} w-50">
                         <img src='${URL.createObjectURL(fileInput.files[i])}' class='img-grid'"/>
                         <button type="button" onclick="deleteFile(${fileList.length - 1})" class="btn p-0 delete-btn"><i class="fa-regular fa-circle-xmark display-4 x-dugme"></i></button>
                    </div>`;
               }
               else{
                    imageWrap.innerHTML += 
                    `<div class="position-relative p-2 div${fileList.length - 1} w-50">
                         <video class='img-grid' controls src='${URL.createObjectURL(fileInput.files[i])}'></video>
                         <button type="button" onclick="deleteFile(${fileList.length - 1})" class="btn p-0 delete-btn"><i class="fa-regular fa-circle-xmark display-4 x-dugme"></i></button>
                    </div>`;
               }
          }
          fileInput.value = '';
          updateDataTransfer();
     }

     function updateDataTransfer(){
          dataTransfer.items.clear();
          fileList.forEach(function(file){
               if(typeof file !== "undefined") dataTransfer.items.add(file)
          });
     }

     function updateFileList(){
          fileInput.files = dataTransfer.files;
     }

     function deleteFile(id){
          fileList[id] = undefined;
          var div = document.getElementsByClassName(`div${id}`)[0];
          div.remove();
          updateDataTransfer();
     }

     $(document).ready(function () {
          $('.comment').hover(function () {
               $(this).children().addClass('fa-solid');
          }, function () {
               $(this).children().removeClass('fa-solid');
          });
          $('.linkSpanner').hover(function(){
               $(this).parent().parent().css("background-color", "#F5F8FA");
          },function(){
               $(this).parent().parent().css("background-color", "white");
          });

          $("#upload-files").submit(function(e){
               e.preventDefault();
               var entity_id = $(this).attr('data-id');
               updateFileList();
               var formData = new FormData(this);
               for (let i = 0; i < fileInput.files.length; i++) {
                    formData.append('files' + i, fileInput.files[i]);
               }
               formData.append('entity_id', entity_id);
               $.ajaxSetup({
                    headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
               });
               $.ajax({
                    type: 'POST',
                    url: "{{route('comment')}}",
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function (response) {
                         location.reload();
                    },
                    statusCode: {
                         422: function(data) {
                              $('.start-section').prepend(`
                              <div class='alert alert-danger'>
                                   <p class='m-0 text-center'>
                                        Incorrect file type!
                                   </p>
                              </div>`);
                              $(".alert").delay(2000).fadeOut(1000, function() {
                              $( this ).remove();
                              });
                         }  
                    }
               });
          });
    });
</script>

@endsection