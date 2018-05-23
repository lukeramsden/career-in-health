@extends('layouts.app')
@section('content')
    <div class="container-fluid mt-lg-4">
        <div class="grid">
            @include('company._dash-collection', ['items' => $items])
        </div>
        @if($items->hasPages())
            <div class="row">
                <div class="col"></div>
                <div class="col-auto">
                    <button class="btn btn-action px-5 my-5 load-more" onclick="loadMore()">Load More</button>
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

        var page = 1;
        const lastPage = {{ $items->lastPage() }}

        function loadMore() {
            let $load = $('.load-more');
            
            if($load.hasClass('disabled'))
                return false;
            
            $load.addClass('disabled');
            
            axios.post('{{ route('dashboard.get') }}', { page: page += 1 })
                .then((resp) => {
                    const $items = $(resp.data);
                    
                    // append items to grid
                    $('.grid').append( $items )
                      // add and lay out newly appended items
                      .masonry( 'appended', $items );
                    
                    if(page >= lastPage) {
                        $load.removeClass('btn-action');
                        $load.addClass('btn-secondary');
                        $load.addClass('disabled');
                        $load.text('Nothing Else To Load!');
                    } else  $load.removeClass('disabled');
                })
                .catch((err) => console.log(err));
            
            return false;
        }
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
        
        @media (max-width: 850px) {
            .grid-sizer,
            .grid-item {
                width: 100%;
                padding: 0;
                padding-bottom: 1rem;
            }
            
            .grid-item--width-full {
                width: 100%;
            }
        }
    </style>
@endsection