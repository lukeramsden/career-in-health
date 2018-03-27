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
                            <hr><p class="small mb-2 text-muted">Not shown to applicant</p>
                            <textarea class="form-control mb-3" rows="3" placeholder="Notes" oninput="dUpdateNotes({{ $application->id }}, this.value)">{{ $application->notes }}</textarea>
                            <select class="custom-select" onchange="updateStatus({{ $application->id }}, this.value)">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.5/lodash.min.js"></script>
    <script>
        const dUpdateNotes = _.debounce(updateNotes, 600);
        function updateStatus(id, val)
        {
            console.log(id + ' - ' + val);
            var url = '{{ route('advert-application.update', 'xtemp') }}';
            axios.post(url.replace('xtemp', id), { 'status': val })
                .then(function(resp) {
                    console.log(resp);
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
                .catch(function(err) {
                    console.log(err);
                });
        }
        
        function updateNotes(id, val)
        {
            console.log(id + ' - ' + val);
            var url = '{{ route('advert-application.update', 'xtemp') }}';
            axios.post(url.replace('xtemp', id), { 'notes': val })
                .then(function(resp) {
                    console.log(resp);
                })
                .catch(function(err) {
                    console.log(err);
                });
        }
    </script>
@endsection