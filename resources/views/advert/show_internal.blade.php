@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <p>{{ $advert->title }}</p>
        <div class="row">
            @foreach($advert->applications as $application)
                <div class="col-md-3 mb-4">
                    <div class="card
                    @switch($application->status)
                    @case(1)
                    border-success
                            @break
                    @case(2)
                    border-danger
                            @break
                    @case(3)
                    border-info
                            @break
                    @case(4)
                    border-secondary
                            @break
                    @endswitch
                    " id="appl-card-{{$application->id}}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $application->user->profile->first_name . ' ' . $application->user->profile->last_name }}</h5>
                            <p class="card-text">{{ $application->custom_cover_letter or 'Cover letter is empty' }}</p>
                            <select class="custom-select" onchange="sendUpdate({{ $application->id }}, this.value)">
                                <option {{ !isset($application->status) ? 'selected' : '' }} disabled>-</option>
                                @foreach(App\Models\AdvertApplication::$statuses as $id => $status)
                                    <option {{ $application->status == $id ? 'selected' : '' }} value="{{ $id }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@section('script')
    <script>
        function sendUpdate(id, val)
        {
            console.log(id + ' - ' + val);
            var url = '{{ route('advert-application.update', 'xtemp1') }}';
            axios.post(url.replace('xtemp1', id), { 'status': val })
                .then(function(response) {
                    console.log(response);
                    var css;
                    switch(parseInt(val)) {
                        case 1:
                            css = 'border-success';
                            break;
                        case 2:
                            css = 'border-danger';
                            break;
                        case 3:
                            css = 'border-info';
                            break;
                        case 4:
                            css = 'border-secondary';
                            break;
                    }
                    $('#appl-card-' + id).attr('class', 'card ' + css)
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
    </script>
@endsection