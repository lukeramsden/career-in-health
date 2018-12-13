@extends('layouts.base')
@section('base_content')
    @includeWhen(!Auth::check(), 'partials.top-navbar')
    
    {{--main app--}}
    <div id="app" class="{{Auth::check()?'side-navbar':'top-navbar'}}">
        <div>
            @auth
                <latest-notifications></latest-notifications>
            @endauth
            <notifications group="notifications"
                           position="bottom right">
                <template slot="body" slot-scope="props">
                    <notification :model="props.item.data" @click="props.close" style="margin-bottom: 0.8rem;" />
                </template>
            </notifications>
            @yield('content')
        </div>
    </div>
    
    @includeWhen(Auth::check(), 'partials.side-navbar')
@endsection
@section('head')
    <link href="/open-iconic/font/css/open-iconic-bootstrap.css" rel="stylesheet">
    @yield('stylesheet')
@endsection
@section('body-end')
    @auth
        <script>
        window.store.commit( 'updateUserType', '{{ $userType }}' );
        </script>
    @endauth
    @yield('pre-script')
    @yield('script')
@endsection