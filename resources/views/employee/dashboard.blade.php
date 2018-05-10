@extends('layouts.app')
@section('content')
    <div class="container-fluid mt-4">
        <div class="grid">
            @each('employee._dash-item', $results->items(), 'item')
        </div>
        @if($results->hasPages())
            <div class="row">
                <div class="col"></div>
                <div class="col-auto">
                    {{-- TODO: Make this do something --}}
                    <button class="btn btn-action px-5 my-5">Load More</button>
                </div>
                <div class="col"></div>
            </div>
        @endif
    </div>
@endsection
@section('script')
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    <script>
        $(function() {
            $('.grid').masonry();
        });
    </script>
@endsection
@section('stylesheet')
    <style>
        .grid-sizer,
        .grid-item {
            width: 50%;
            padding: 1rem;
        }
        
        .grid-item--width-full {
            width: 100%;
        }
    </style>
@endsection