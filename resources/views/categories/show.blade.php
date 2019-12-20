@extends('layouts.global')
@section('title') Detail Category @endsection
@section('content')
	<div class="col-md-8">
	 <div class="card">
	 <div class="card-body">
	 <b>Name:</b> <br/>
	 {{$category->name}}
	 <br><br>
	 @if($category->image)
	 <img src="{{asset('storage/'. $category->image)}}" width="128px"/>
	 @else
	 No avatar
	 @endif
	 <br>
	 <br>
	
	 </div>
	 </div>
	 </div>

@endsection