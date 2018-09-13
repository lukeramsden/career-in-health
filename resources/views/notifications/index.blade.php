@extends('layouts.app')
@section('content')
    <div class="container-fluid mt-5">
        <div class="card-columns column-count-2 column-count-md-3 column-count-lg-4 column-count-xl-5">
            @foreach($notifications as $notif)
                @switch($notif->type)
                    @case(\App\Notifications\ReceivedPrivateMessage::class)
                        <div id="notification-{{$notif->id}}"
                             class="card card-custom {{$notif->unread()?'card-custom-primary':'card-custom-secondary'}}">
                            <div class="card-header pl-3 p-2">Message from
                                <b>{{ $notif->data['sender_name'] }}</b></div>
                            <div class="card-body">
                                <p>{{ str_limit($notif->data['body']) }}</p>
                                <hr>
                                <p class="mb-0 text-right small">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="card-footer p-0">
                                <div class="btn-group btn-group-full btn-group-sm">
                                    <a
                                    href="{{ action('NotificationController@clickThrough', ['notification' => $notif]) }}"
                                    class="btn btn-primary" title="View Message">
                                        <span class="oi oi-comment-square"></span>
                                    </a>
                                    @if($notif->unread())
                                        <button class="btn btn-dark-primary" title="Mark As Read" onclick="markNotifRead(this)"><span
                                            class="oi oi-check"></span></button>
                                    @endif
                                    <button class="btn btn-danger" title="Delete" onclick="deleteNotif(this)"><span
                                        class="oi oi-trash"></span></button>
                                </div>
                            </div>
                        </div>
                        @break
                    @case(\App\Notifications\CompanyReceivedListingApplication::class)
                        <div id="notification-{{$notif->id}}"
                             class="card card-custom {{$notif->unread()?'card-custom-primary':'card-custom-secondary'}}">
                            <div class="card-header pl-3 p-2">Message from
                                <b>{{ $notif->data['sender_name'] }}</b></div>
                            <div class="card-body">
                                @if($notif->data['body'])
                                    <p>{{ str_limit($notif->data['body']) }}</p>
                                @else
                                    <p><span class="text-muted font-italic">No cover letter</span></p>
                                @endif
                                <hr>
                                <p class="mb-0 text-right small">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="card-footer p-0">
                                <div class="btn-group btn-group-full btn-group-sm">
                                    <a
                                    href="{{ action('NotificationController@clickThrough', ['notification' => $notif]) }}"
                                    class="btn btn-primary" title="View Message">
                                        <span class="oi oi-comment-square"></span>
                                    </a>
                                    @if($notif->unread())
                                        <button class="btn btn-dark-primary" title="Mark As Read" onclick="markNotifRead(this)"><span
                                            class="oi oi-check"></span></button>
                                    @endif
                                    <button class="btn btn-danger" title="Delete" onclick="deleteNotif(this)"><span
                                        class="oi oi-trash"></span></button>
                                </div>
                            </div>
                        </div>
                        @break
                @endswitch
            @endforeach
        </div>
        {!! $notifications->appends(Request::capture()->except('page'))->render('vendor.pagination') !!}
    </div>
@endsection
@section('script')
    <script>
        function deleteNotif(self) {
            var $self = $(self);
            $self.prop('disabled', true);
            var $parent = $self.parents('div.card.card-custom');
            var notifId = $parent.prop('id').split('notification-')[1];
            var action = route('notifications.delete', {'notification': notifId});
            axios
                .post(action.toString())
                .then(function (res) {
                    if (res.data.success) {
                        $parent.remove();
                    } else {
                        toastr.error('Could not delete.');
                    }
                })
                .catch(function (e) {
                    console.log(e);
                    toastr.error('Error.');
                })
                .then(function () {
                    $self.prop('disabled.false');
                });
        }
        
        function markNotifRead(self) {
            $self = $(self);
            $self.prop('disabled', true);
            var $parent = $self.parents('div.card.card-custom');
            var notifId = $parent.prop('id').split('notification-')[1];
            var action = route('notifications.mark-as-read', {'notification': notifId});
            axios
                .post(action.toString())
                .then(function (res) {
                    if (res.data.success) {
                        $parent
                            .removeClass('card-custom-primary')
                            .addClass('card-custom-secondary');
                        $self.remove();
                    } else {
                        toastr.error('Could not delete.');
                    }
                })
                .catch(function (e) {
                    console.log(e);
                    toastr.error('Error.');
                })
                .then(function () {
                    $self.prop('disabled.false');
                });
        }
    </script>
@endsection