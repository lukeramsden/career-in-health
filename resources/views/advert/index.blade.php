@extends('layouts.app')
@section('content')

    <div class="container">
        <div class='create-advert-container has-top-bar'>

            <div class='row first-row'>
                <div class='col-md-12 form-section'>
                    <h1>All Adverts</h1>
                </div>
            </div>

            <div class='row'>
                <div class='col-md-12 form-section'>
                    <table class='table'>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($adverts as $advert)
                                <tr>
                                    <td>
                                        <p>{{ $advert->title }}</p>
                                    </td>
                                    <td>
                                        <a href='{{ route('advert_show_internal', ['advert' => $advert]) }}' class='btn btn-outline-primary btn-sm'>View</a>
                                        <a href='{{ $advert->linkEdit() }}' class='btn btn-action btn-sm'>Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>

@endsection