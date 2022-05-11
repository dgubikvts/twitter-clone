@extends('layouts.app')

@section('content')

<img width="250"src="{{ asset('storage/images/' . $file->getClientOriginalName())}}" alt="">
{{$file->getClientOriginalName()}}
{{$path}}
{{asset('storage')}}
@endsection