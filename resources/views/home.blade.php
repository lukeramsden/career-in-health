@extends('layouts.base')
@section('b_content')
    <div class="container-fluid">
        <a href="{{ route('login') }}" class="btn btn-action btn-xl">Log In</a>
        <a href="{{ route('register') }}" class="btn btn-action btn-xl">Register</a>
        <a href="{{ route('search') }}" class="btn btn-action btn-xl">Search</a>
    </div>
@endsection