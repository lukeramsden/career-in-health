@switch($item->_feed_type)
    @case('advert')
        <div class="grid-item">
            <div class="card card-custom card-advert">
                <div class="card-header"><span class="font-italic">A job that might interest you...</span></div>
                <div class="card-body">
                    <a href="{{ route('company.show', [$item->company]) }}" class="card-subtitle">
                        {{$item->company->name}}
                    </a>
                    <h4 class="card-title">{{$item->jobRole->name}}</h4>
                    <h5>{{ $item->title }}</h5>
                    <h6>{{ $item->getSetting() }}</h6>
                    <div id="small-details">
                        <div>
                            <p><span class="badge badge-primary badge-pill p-2 px-3">{{ $item->getType() }}</span></p>
                        </div>
                        <div>
                            <p><span class="oi oi-map-marker mr-3"></span>{{ $item->address->location->name }}</p>
                        </div>
                        <div>
                            <p>
                                @money($item->min_salary * 100, 'GBP') - @money($item->max_salary * 100, 'GBP')
                            </p>
                        </div>
                        <div>
                            <p><a href="{{ route('tracking.advert.recommended.click', ['advert' => $item]) }}" class="btn btn-action btn-sm btn-block badge-pill px-3">More Details</a></p>
                        </div>
                    </div>
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
                            {{ str_limit($item->custom_cover_letter, 200) }}
                        @else
                            <span class="text-muted font-italic">No cover letter</span>
                        @endif
                    </p>
                    <span class="badge badge-primary badge-pill p-2 px-3">
                        {{ \App\AdvertApplication::$statuses[$item->status ?? 0] }}
                    </span>
                </div>
                <div class="card-footer">{{ $item->updated_at->diffForHumans() }}</div>
            </div>
        </div>
        @break
@endswitch
