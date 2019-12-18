@extends("layouts.global")
@section("title") Users list @endsection
@section("content")

 @foreach($users as $user)
 - {{$user->email}} <br/>
@endforeach

@endsection