@extends('layouts.app')

@section('content')

@if(Auth::check() or Auth::viaRemember())
<app :username="{{ json_encode(Auth::user()->name) }}"
    :user_id="{{ json_encode(Auth::user()->id) }}"
    ></app>
@else
<script>window.location = "/login";</script>
@endif
@endsection
