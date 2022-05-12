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
       <div class="col-5 border-top border-start border-end border-light p-0">
          @foreach ($errors->all() as $error)
          <div class="alert alert-danger" role="alert">
          {{ $error }}
          </div>
          @endforeach
          <form action="{{ route('tweet') }}" method="POST" class="d-flex mt-3 p-2 border-bottom border-light" enctype="multipart/form-data" id="upload-files">
               @csrf
               <div class="col-2">
                    <a href="/{{Auth::user()->username}}"><img src="{{asset(Auth::user()->profileImage->path)}}" alt="" class="img profile-image rounded-circle mx-auto d-block"></a>
               </div>
               <div class="d-flex col-10 flex-column parent">
                    <textarea name="content" class="border-0 form-control textarea" maxlength="280" rows="3" placeholder="What's happening?" required></textarea>
                    <div class="d-flex align-items-center justify-content-between insert-before">
                         <div class="d-flex">
                              <div class="file-upload">
                                   <input type="file" name="files[]" id="file" accept="image/*, video/*" onchange="loadFile(this)" multiple><label for="file"><i class="fa-regular fa-image twitter-color me-2"></i></label>
                              </div>
                         </div>
                         <div class="tweet">
                              <button type="submit" name="action" value="{{ route('tweet') }}" class="btn dugme rounded-pill text-white me-2">Tweet</button>
                         </div>
                    </div>
               </div>
          </form>        
          @forelse($entities as $entity)
          @if($entity->post)
          <div class="border-bottom border-light m-0 p-2 row position-relative">
               <div class="col-2 p-0 mt-3 above">
                    <a href="/{{$entity->user->username}}"><img src="{{asset($entity->user->profileImage->path)}}" alt="" class="img profile-image rounded-circle mx-auto d-block"></a>
               </div>
               <div class="col-10 p-0 mt-3">
                    <div class="d-flex align-items-center">
                         <a href="/{{$entity->user->username}}" class="above username"><h5 class="m-0 me-3 fw-bold">{{$entity->user->name}}</h5></a>
                         <h6 class="m-0 above"><a href="/{{$entity->user->username}}" class="text-decoration-none atcolor">{{'@'.$entity->user->username}}</a></h6>
                    </div>
                    <p class="lead mt-2">{{$entity->content}}</p>
                    @if($entity->files)
                         @foreach($entity->files as $file)
                              @if($file->type == 'image')
                              <img src="{{asset($file->path)}}" width="100%" class="img rounded mb-2">
                              @elseif($file->type == 'video')
                              <div class="position-relative above">
                                   <video src="{{asset($file->path)}}" class="w-100" controls></video>
                              </div>
                              @endif
                         @endforeach
                         
                    @endif
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
       <div class="col-3">
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
     var fileList = [];
     var fileInput = document.getElementById('file');
     var dataTransfer = new DataTransfer();
          

     function loadFile(fileInput){
          var div = document.getElementsByClassName('insert-before')[0];
          var parent = document.getElementsByClassName('parent')[0];
          var images = ['image/jpg', 'image/png', 'image/jpeg', 'image/gif'];
          for(var i = 0; i < fileInput.files.length; i++){
               fileList.push(fileInput.files[i]);
               var insideDiv = document.createElement("div");
               insideDiv.classList.add("col-12", "mb-2", "p-2", "position-relative", `div${fileList.length - 1}`);
               if(images.includes(fileInput.files[i].type)){
                    insideDiv.innerHTML += 
                    `<img src='${URL.createObjectURL(fileInput.files[i])}' class='w-100'"/>
                    <button type="button" onclick="deleteFile(${fileList.length - 1})" class="btn p-0 delete-btn"><i class="fa-regular fa-circle-xmark display-4 x-dugme"></i></button>
                    `;
               }
               else{
                    insideDiv.innerHTML += 
                    `<video class='w-100' controls src='${URL.createObjectURL(fileInput.files[i])}'></video>
                    <button type="button" onclick="deleteFile(${fileList.length - 1})" class="btn p-0 delete-btn"><i class="fa-regular fa-circle-xmark display-4 x-dugme"></i></button>
                    `;
               }
               parent.insertBefore(insideDiv, div);
          }
          updateFileList();
          console.log(fileList);
     }


     function updateFileList(){
          dataTransfer.items.clear();
          fileList.forEach(function(file){
               if(typeof file !== "undefined") dataTransfer.items.add(file)
          });
          fileInput.files = dataTransfer.files;
     }

     function deleteFile(id){
          fileList[id] = undefined;
          var div = document.getElementsByClassName(`div${id}`)[0];
          div.remove();
          updateFileList();
          console.log(fileList);
     }

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



     $("#upload-files").submit(function(e){
          e.preventDefault();
          console.log(fileInput.files);
          var formData = new FormData(this);
          for (let i = 0; i < fileInput.files.length; i++) {
               formData.append('files' + i, fileInput.files[i]);
          }

          console.log(formData);
          
          $.ajaxSetup({
               headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
          });
          $.ajax({
               type: 'POST',
               url: "{{route('tweet')}}",
               data: formData,
               cache:false,
               contentType: false,
               processData: false,
               dataType: 'json',
               success: function (response) {
                    location.reload();
               
               },
               error: function(response){
                    console.log(response.errors);
               }
          });
     });

    });
</script>

@endsection
