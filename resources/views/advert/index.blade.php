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
                                <th>Location</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($adverts as $advert)
                                <tr>
                                    <td>
                                        <p>{{ $advert->title }}</p>
                                        @foreach($advert->applications as $application)
                                            <p>
                                                <small>{{ $application->user->profile->first_name . ' ' . $application->user->profile->last_name}}</small>
                                            </p>
                                        @endforeach
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td>
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