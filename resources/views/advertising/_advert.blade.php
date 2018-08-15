@isset($advert)
    {{-- TODO: got to handle images better, need to ask James --}}
    <a id="advert" href="{{ $advert->links_to }}" target="_blank">
        @isset($advert->image_path)
            <div class="card-advert-image-preview">
                <img src="{{ Storage::url($advert->image_path) }}">
                <div>
                    <h5>{{ $advert->title }}</h5>
                    <p>{{ $advert->body }}</p>
                </div>
            </div>
        @else
            <div class="card-advert-text">
                <div>
                    {{ $advert->title }}
                </div>
                <div>
                    @isset($advert->body)
                        <p>{{ $advert->body }}</p>
                    @endisset
                </div>
            </div>
        @endisset
    </a>
@endisset