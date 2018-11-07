@extends('layouts.base')
@section('base_content')
    @includeWhen(Auth::check(), 'partials.side-navbar')
    @includeWhen(!Auth::check(), 'partials.top-navbar')
    
    {{--main app--}}
    <div id="app" class="{{Auth::check()?'side-navbar':'top-navbar'}}">
        @yield('content')
    </div>
@endsection
@section('head')
    <link href="/open-iconic/font/css/open-iconic-bootstrap.css" rel="stylesheet">
    @yield('stylesheet')
@endsection
@section('body-end')
    @routes
    @auth
        <script>
            function toggleNotificationDrawer()
            {
                $('#navbar-notification-panel').toggleClass('open');
                $('#navbar-notification-toggle').toggleClass('active');
            }
            
            $(function() {
               $('#navbar-notification-panel .mark-as-read').click(function() {
                   var self = $(this);
                   self.prop('disabled', true);
    
                   axios
                       .post('{{ route('notifications.mark-all-as-read') }}')
                       .then(function (resp) {
                           if (resp.data.success)
                           {
                               $('.notification').removeClass('unread');
                               $('#navbar-notification-unread-badge').remove();
                           }
                       })
                       .catch(function (e) {
                           console.log(e);
                           toastr.error('Could not mark notifications as read');
                       })
                       .then(function () {
                           self.prop('disabled', false);
                       })
               })
            });
            
            window.store.commit('updateUserType',
                @usertype('employee')
                    'employee'
                @elseusertype('company')
                    'company'
                @elseusertype
                    ''
                @endusertype
            );
        </script>
    @endauth
    @yield('script')
@endsection