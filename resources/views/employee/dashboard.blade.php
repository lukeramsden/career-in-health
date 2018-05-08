@extends('layouts.app')
@section('content')
    <div class="container-fluid mt-5">
        <div class="grid">
            @foreach($feed as $item)
                @switch($item->_feed_type)
                    @case('advert')
                        <div class="grid-item">
                            <div class="card card-custom">
                                <div class="card-header"><span class="font-italic">A job that might interest you...</span></div>
                                <div class="card-body">
                                    <p>{{ $item->title }}</p>
                                    <p>{{ str_limit($item->description) }}</p>
                                    <p>
                                        @money($item->min_salary * 100, 'GBP') - @money($item->max_salary * 100, 'GBP')
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <span class="oi oi-calendar"></span> <span class="text-muted">Last Updated</span> {{ $item->updated_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        @break;
                    @case('application')
                        <div class="grid-item">
                            <div class="card card-custom">
                                <div class="card-header"><span class="font-italic">One of your applications was recently updated</span></div>
                                <div class="card-body">
                                    <p>
                                        @if($item->custom_cover_letter)
                                            {{ $item->custom_cover_letter }}
                                        @else
                                            <span class="text-muted font-italic">No cover letter</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="card-footer">{{ $item->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @break;
                @endswitch
            @endforeach
        </div>
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