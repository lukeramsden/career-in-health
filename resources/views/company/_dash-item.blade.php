@switch(optional($item)->_feed_type)
    @case('application')
        <div class="grid-item">
            <div class="card card-custom">
                <div class="card-header"><span class="font-italic">Somebody applied to one of your listings</span></div>
                <div class="card-body">
                    <p>
                        @if($item->custom_cover_letter)
                            {{ str_limit($item->custom_cover_letter, 200) }}
                        @else
                            <span class="text-muted font-italic">No cover letter</span>
                        @endif
                    </p>
                    <a href="{{ $item->permalink  }}" class="btn btn-primary btn-sm px-4">View</a>
                </div>
                <div class="card-footer">{{ $item->created_at->diffForHumans() }}</div>
            </div>
        </div>
        @break
@endswitch
