@extends('layouts.app')

@section('content')

@foreach ($errors->all() as $error)
<div class="alert alert-danger" role="alert">
{{ $error }}
</div>
@endforeach
<form method="POST" class="d-flex p-2 mt-3" enctype="multipart/form-data" id="upload-files">
     @csrf
     <div class="col-2">
          <a href="/{{Auth::user()->username}}"><img src="{{asset(Auth::user()->profileImage->path)}}" alt="" class="img profile-image rounded-circle mx-auto d-block"></a>
     </div>
     <div class="d-flex col-10 flex-column">
          <textarea name="content" class="border-0 form-control textarea" maxlength="280" rows="3" placeholder="What's happening?" required></textarea>
          <div class="d-flex flex-wrap images-wrap"></div>
          <div class="d-flex align-items-center justify-content-between">
               <div class="file-upload">
                    <input type="file" name="files[]" id="file" accept="image/*, video/*" onchange="loadFile(this)" multiple><label for="file"><i class="fa-regular fa-image me-2 btn rounded-pill upload-file twitter-color"></i></label>
               </div>
               <div class="tweet">
                    <button type="submit" class="btn dugme rounded-pill text-white me-2">Tweet</button>
               </div>
          </div>
     </div>
</form>        
@forelse($entities as $entity)
     @if($entity->post)
     <div class="border-top border-light m-0 p-2 row position-relative">
          <div class="col-2 p-0 mt-3 d-flex flex-column">
               <a href="/{{$entity->user->username}}" class="above"><img src="{{asset($entity->user->profileImage->path)}}" alt="" class="img profile-image rounded-circle mx-auto d-block"></a>
          </div>
          <div class="col-10 p-0 mt-3">
               <div class="d-flex align-items-center">
                    <a href="/{{$entity->user->username}}" class="above username"><h5 class="m-0 me-3 fw-bold">{{$entity->user->name}}</h5></a>
                    <h6 class="m-0 above"><a href="/{{$entity->user->username}}" class="text-decoration-none atcolor">{{'@'.$entity->user->username}}</a></h6>
               </div>
               <p class="lead mt-2">{{$entity->content}}</p>
               @if($entity->files)
               <div class="d-flex flex-wrap">
                    @foreach($entity->files as $file)
                    <div class="{{$entity->files->count() % 2 !== 0 && $loop->last ? 'w-100' : 'w-50'}} pe-1">
                         @if($file->type == 'image')
                         <img src="{{asset($file->path)}}" class="img rounded mb-2 {{$entity->files->count() == 1 ? 'w-100' : 'img-grid' }}">
                         @elseif($file->type == 'video')
                         <div class="position-relative above">
                              <video src="{{asset($file->path)}}" class="{{$entity->files->count() == 1 ? 'w-100' : 'img-grid'}}" controls></video>
                         </div>
                         @endif
                    </div>
                    @endforeach
               </div>
               @endif
               <form action="{{ route('like') }}" method="POST" class="d-flex justify-content-between likeForm">
                    @csrf
                    <button type="submit" data-id="{{$entity->id}}" class="above btn rounded-pill me-2 hover submitLike"><i class="fa-heart {{App\Http\Controllers\PostsController::isLiked($entity) ? 'fa-solid' : 'fa-regular'}} text-danger me-2"></i>{{$entity->amountOfLikes}}</button>
                    <button type="button" class="above btn rounded-pill comment hover"><i class="fa-regular fa-comment me-2"></i>{{$entity->amountOfComments}}</button>
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
       

<script>
     var fileList = [];
     var fileInput = document.getElementById('file');
     var dataTransfer = new DataTransfer();

     function loadFile(fileInput){
          var imageWrap = document.getElementsByClassName('images-wrap')[0];
          var images = ['image/jpg', 'image/png', 'image/jpeg', 'image/gif', 'image/bmp'];
          var count = 0;
          for(var i = 0; i < fileInput.files.length; i++){
               fileList.push(fileInput.files[i]);
               if(images.includes(fileInput.files[i].type)){
                    imageWrap.innerHTML += 
                    `<div class="position-relative p-2 div${fileList.length - 1} w-50 showcase">
                         <img src='${URL.createObjectURL(fileInput.files[i])}' class='img-grid'"/>
                         <button type="button" onclick="deleteFile(${fileList.length - 1})" class="btn p-0 delete-btn"><i class="fa-regular fa-circle-xmark display-4 x-dugme"></i></button>
                    </div>`;
               }
               else{
                    imageWrap.innerHTML += 
                    `<div class="position-relative p-2 div${fileList.length - 1} w-50 showcase">
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

     function insertAfter(referenceNode, newNode) {
          referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
     }

     
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
          
          function addTweet(tweet, user, profileImage, files){
               var div = document.createElement('div');
               $(div).attr('class', 'border-top border-light m-0 p-2 row position-relative');
               $(div).append( 
               `<div class="col-2 p-0 mt-3 d-flex flex-column">
                    <a href="/${user.username}" class="above"><img src="${profileImage}" alt="" class="img profile-image rounded-circle mx-auto d-block"></a>
               </div>
               <div class="col-10 p-0 mt-3 append-form${tweet.id}">
                    <div class="d-flex align-items-center">
                         <a href="/${user.username}" class="above username"><h5 class="m-0 me-3 fw-bold">${user.name}</h5></a>
                         <h6 class="m-0 above"><a href="/${user.username}" class="text-decoration-none atcolor">@${user.username}</a></h6>
                    </div>
                    <p class="lead mt-2">${tweet.content}</p>`);
                    $(div).insertAfter("#upload-files");
                    if(files.length > 0){
                         $(`.append-form${tweet.id}`).append(`<div class="d-flex flex-wrap insert-file${tweet.id}">`);
                         for(var i = 0; i < files.length; i++){
                              if(files.length % 2 !== 0 && i == files.length - 1)
                                   $(`.insert-file${tweet.id}`).append(`<div class='w-100 pe-1 file-place${tweet.id}-${i}'>`);
                              else
                                   $(`.insert-file${tweet.id}`).append(`<div class='w-50 pe-1 file-place${tweet.id}-${i}'>`);
                              
                              if(files[i].type == 'image'){
                                   if(files.length == 1)
                                        $(`.file-place${tweet.id}-${i}`).append(`<img src="${files[i].path}" class="img rounded mb-2 w-100">`);
                                   else
                                        $(`.file-place${tweet.id}-${i}`).append(`<img src="${files[i].path}" class="img rounded mb-2 img-grid">`);
                              }
                              else if(files[i].type == 'video'){
                                   $(`.file-place${tweet.id}-${i}`).append(`<div class='position-relative above video-place${tweet.id}-${i}'>`);
                                   if(files.length == 1)
                                        $(`.video-place${tweet.id}-${i}`).append(`<video src="${files[i].path}" class="w-100" controls></video> </div>`);
                                   else
                                        $(`.video-place${tweet.id}-${i}`).append(`<video src="${files[i].path}" class="img-grid" controls></video> </div>`);
                              }
                         }
                    }
                    var form = document.createElement("form");
                    $(form).attr('class', 'd-flex justify-content-between likeForm');
                    $(form).attr('method', 'POST');
                    var url = '{{ route("view.tweet", ":id") }}';
                    url = url.replace(':id', tweet.id);
                    $(form).append( 
                              `
                              <button type="submit" data-id="${tweet.id}" class="above btn rounded-pill me-2 hover submitLike"><i class="fa-heart fa-regular text-danger me-2"></i>${tweet.amountOfLikes}</button>
                              <button type="button" class="above btn rounded-pill comment hover"><i class="fa-regular fa-comment me-2"></i>${tweet.amountOfComments}</button>
                              <button type="button" class="above btn rounded-pill hover"><i class="fa-solid fa-retweet me-2"></i></button>
                         </form>
                    </div>`);
          $(`.append-form${tweet.id}`).append(form);
          var a = document.createElement('a');
          a.href = url;
          a.innerHTML = `<span class="linkSpanner"></span></div>`;
          div.appendChild(a);
          }




          $("#upload-files").submit(function(e){
               e.preventDefault();
               var submitBtn = $(this).find("button");
               submitBtn.prop('disabled', true);
               updateFileList();
               var formData = new FormData(this);
               for (let i = 0; i < fileInput.files.length; i++) {
                    formData.append('files' + i, fileInput.files[i]);
               }
               
               $.ajaxSetup({
                    headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
               });
               $.ajax({
                    type: 'POST',
                    url: "{{ route('tweet') }}",
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function (response) {
                         addTweet(response.tweet, response.tweet.user, response.tweet.user.profile_image.path, response.files);
                         submitBtn.prop('disabled', false);
                         fileList = [];
                         $('.textarea').val('');
                         $('.showcase').remove();
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
     });
</script>

@endsection