@extends('layouts.base')
@section('base_content')
    @includeWhen(!Auth::check(), 'partials.top-navbar')
    
    {{--main app--}}
    <div id="app" class="{{Auth::check()?'side-navbar':'top-navbar'}}">
        @yield('content')
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
        function toggleNotificationDrawer()
        {
            $( '#navbar-notification-panel' ).toggleClass( 'open' );
            $( '#navbar-notification-toggle' ).toggleClass( 'active' );
        }
        
        $( function ()
        {
            $( '#navbar-notification-panel .mark-as-read' ).click( function ()
            {
                var self = $( this );
                self.prop( 'disabled', true );
                
                axios
                    .post( '{{ route('notifications.mark-all-as-read') }}' )
                    .then( function ( res )
                    {
                        if ( res.data.success )
                        {
                            $( '.notification' ).removeClass( 'unread' );
                            $( '#navbar-notification-unread-badge' ).remove();
                        }
                    } )
                    .catch( function ( e )
                    {
                        console.log( e );
                        toastr.error( 'Could not mark notifications as read' );
                    } )
                    .then( function ()
                    {
                        self.prop( 'disabled', false );
                    } );
            } );
            
            const $app = $( '#app' );
            $( '#navbar-opener' ).click( () => $app.removeClass( 'navbar-collapsed' ) );
            $( '#navbar-closer' ).click( () => $app.addClass( 'navbar-collapsed' ) );
        } );
        
        window.store.commit( 'updateUserType', '{{ $userType }}' );
        </script>
    @endauth
    @yield('pre-script')
    @yield('script')
@endsection