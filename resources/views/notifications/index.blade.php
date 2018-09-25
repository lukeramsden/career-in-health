@extends('layouts.app')
@section('content')
    <div class="container-fluid p-0 m-0">
        <div class="row m-0 p-4" id="job_listing-show-row">
            @verbatim
                <div class="col-12">
                    <div class="row">
                        <div class="col-12 col-lg-4 order-lg-last">
                            <div class="position-sticky top-4">
                                <div class="card card-custom-material"
                                     id="notification-filter-card">
                                    <div class="card-body p-0">
                                        <input class="input input-material w-100 p-3" placeholder="Search"
                                               id="input-query"
                                               type="text" v-model="query">
                                    </div>
                                </div>
                                <div class="card card-custom-material card-custom-no-top-bar mt-4"
                                     id="notification-actions-card">
                                    <div class="card-body p-0">
                                        <div class="btn-group btn-group-full">
                                            <button class="btn btn-primary" v-on:click.stop.prevent="markAllAsRead">Mark All As Read</button>
                                            <button class="btn btn-danger" v-on:click.stop.prevent="deleteAllRead">Delete All Read</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-8">
                            <div class="table-responsive">
                                <table class="table w-100">
                                    <thead class="thead-primary text-light">
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="result of queryResults">
                                        <td>
                                            <template v-for="key of dataKeys(result)">
                                                <p class="d-inline-block mr-5 mb-0">
                                                    <b>{{ changeCase.titleCase(key) }}</b><br>
                                                    {{ result.data[key] }}
                                                </p>
                                            </template>
                                        </td>
                                        <td>
                                            {{createdAtDiff(result)}}
                                            <span v-if="result.read_at == null" class="badge badge-info ml-2 p-2">UNREAD</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endverbatim
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.16/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.5/lodash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    
    <script>
        toastr.options = {
            'closeButton': true,
            'newestOnTop': false,
            'positionClass': 'toast-top-right',
            'progressBar': true,
        };

        let data = {
            notifications: {!! json_encode($notifications->get()) !!},
            query: '',
        };

        const app = new Vue({
            el: '#app',
            data: data,
            methods: {
                dataKeys(n) {
                    return _
                        .chain(n.data)
                        .omit(['action'])
                        .thru(v => Object.keys(v))
                        .value()
                        ;
                },
                createdAtDiff(a) {
                    return moment.utc(a.created_at).local().fromNow();
                },
                markAllAsRead(e) {
                    var self = this;
                    var $self = $(e.target);
                    $self.prop('disabled', true);
                    axios
                        .post('{{ route('notifications.mark-all-as-read') }}')
                        .then(function (resp) {
                            if (resp.data.success)
                                self.notifications.map(a => a.read_at = moment());
                        })
                        .catch(function (e) {
                            console.log(e);
                            toastr.error('Could not mark notifications as read');
                        })
                        .then(function () {
                            $self.prop('disabled', false);
                        })
                },
                deleteAllRead(e) {
                    var self = this;
                    var $self = $(e.target);
                    $self.prop('disabled', true);
                    axios
                        .post('{{ route('notifications.delete-all-read') }}')
                        .then(function (resp) {
                            if (resp.data.success)
                                self.notifications =
                                    self.notifications.filter(a => a.read_at == null);
                            
                            console.log(JSON.parse(JSON.stringify(self.notifications)));
                        })
                        .catch(function (e) {
                            console.log(e);
                            toastr.error('Could not delete read notifications');
                        })
                        .then(function () {
                            $self.prop('disabled', false);
                        })
                },
            },
            computed: {
                queryResults() {
                    // Don't bother with scoring anything if the query is empty.
                    if (!this.query) return this.notifications;

                    // Preparing the query before-hand lets fuzzaldrin-plus optimize things a bit.
                    const preparedQuery = fuzzaldrin.prepareQuery(this.query);
                    // We use this to keep track of the similarity for each option.
                    const scores = {};

                    return this.notifications
                    // Score each option & create a new array out of them.
                        .map((notification, index) => {
                            // See how well each field compares to the query.
                            // Scores are a non-normalized number
                            // representing how similar the query is to the field.
                            const fieldScores = _
                                .chain(this.dataKeys(notification))
                                .map(k => notification.data[k])
                                .map(field => fuzzaldrin.score(field, this.query, {preparedQuery}))
                                .value()
                            ;

                            // Store the highest score for this option
                            // so we can compare it to other options.
                            scores[notification.id] = Math.max(...fieldScores);

                            return notification;
                        })
                        // Remove anything with a really low score.
                        // You might want to play around with this.
                        .filter(notification => scores[notification.id] > 1)
                        // Finally, sort by the highest score.
                        .sort((a, b) => scores[b.id] - scores[a.id])
                        ;
                },
            },
        });
        
        @foreach ($errors->all() as $error)
        toastr.error("{{ $error }}");
        @endforeach
    </script>
@endsection
@section('stylesheet')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endsection

{{--@extends('layouts.app')--}}
{{--@section('content')--}}
{{--<div class="container-fluid mt-5">--}}
{{--<div class="card-columns column-count-2 column-count-md-3 column-count-lg-4 column-count-xl-5">--}}
{{--@foreach($notifications as $notif)--}}
{{--@switch($notif->type)--}}
{{--@case(\App\Notifications\ReceivedPrivateMessage::class)--}}
{{--<div id="notification-{{$notif->id}}"--}}
{{--class="card card-custom {{$notif->unread()?'card-custom-primary':'card-custom-secondary'}}">--}}
{{--<div class="card-header pl-3 p-2">Message from--}}
{{--<b>{{ $notif->data['sender_name'] }}</b></div>--}}
{{--<div class="card-body">--}}
{{--<p>{{ str_limit($notif->data['body']) }}</p>--}}
{{--<hr>--}}
{{--<p class="mb-0 text-right small">{{ $notif->created_at->diffForHumans() }}</p>--}}
{{--</div>--}}
{{--<div class="card-footer p-0">--}}
{{--<div class="btn-group btn-group-full btn-group-sm">--}}
{{--<a--}}
{{--href="{{ action('NotificationController@clickThrough', ['notification' => $notif]) }}"--}}
{{--class="btn btn-primary" title="View Message">--}}
{{--<span class="oi oi-comment-square"></span>--}}
{{--</a>--}}
{{--@if($notif->unread())--}}
{{--<button class="btn btn-dark-primary" title="Mark As Read" onclick="markNotifRead(this)"><span--}}
{{--class="oi oi-check"></span></button>--}}
{{--@endif--}}
{{--<button class="btn btn-danger" title="Delete" onclick="deleteNotif(this)"><span--}}
{{--class="oi oi-trash"></span></button>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--@break--}}
{{--@case(\App\Notifications\CompanyReceivedListingApplication::class)--}}
{{--<div id="notification-{{$notif->id}}"--}}
{{--class="card card-custom {{$notif->unread()?'card-custom-primary':'card-custom-secondary'}}">--}}
{{--<div class="card-header pl-3 p-2">Message from--}}
{{--<b>{{ $notif->data['sender_name'] }}</b></div>--}}
{{--<div class="card-body">--}}
{{--@if($notif->data['body'])--}}
{{--<p>{{ str_limit($notif->data['body']) }}</p>--}}
{{--@else--}}
{{--<p><span class="text-muted font-italic">No cover letter</span></p>--}}
{{--@endif--}}
{{--<hr>--}}
{{--<p class="mb-0 text-right small">{{ $notif->created_at->diffForHumans() }}</p>--}}
{{--</div>--}}
{{--<div class="card-footer p-0">--}}
{{--<div class="btn-group btn-group-full btn-group-sm">--}}
{{--<a--}}
{{--href="{{ action('NotificationController@clickThrough', ['notification' => $notif]) }}"--}}
{{--class="btn btn-primary" title="View Message">--}}
{{--<span class="oi oi-comment-square"></span>--}}
{{--</a>--}}
{{--@if($notif->unread())--}}
{{--<button class="btn btn-dark-primary" title="Mark As Read" onclick="markNotifRead(this)"><span--}}
{{--class="oi oi-check"></span></button>--}}
{{--@endif--}}
{{--<button class="btn btn-danger" title="Delete" onclick="deleteNotif(this)"><span--}}
{{--class="oi oi-trash"></span></button>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--@break--}}
{{--@endswitch--}}
{{--@endforeach--}}
{{--</div>--}}
{{--</div>--}}
{{--@endsection--}}
{{--@section('script')--}}
{{--<script>--}}
{{--function deleteNotif(self) {--}}
{{--var $self = $(self);--}}
{{--$self.prop('disabled', true);--}}
{{--var $parent = $self.parents('div.card.card-custom');--}}
{{--var notifId = $parent.prop('id').split('notification-')[1];--}}
{{--var action = route('notifications.delete', {'notification': notifId});--}}
{{--axios--}}
{{--.post(action.toString())--}}
{{--.then(function (res) {--}}
{{--if (res.data.success) {--}}
{{--$parent.remove();--}}
{{--} else {--}}
{{--toastr.error('Could not delete.');--}}
{{--}--}}
{{--})--}}
{{--.catch(function (e) {--}}
{{--console.log(e);--}}
{{--toastr.error('Error.');--}}
{{--})--}}
{{--.then(function () {--}}
{{--$self.prop('disabled.false');--}}
{{--});--}}
{{--}--}}
{{----}}
{{--function markNotifRead(self) {--}}
{{--$self = $(self);--}}
{{--$self.prop('disabled', true);--}}
{{--var $parent = $self.parents('div.card.card-custom');--}}
{{--var notifId = $parent.prop('id').split('notification-')[1];--}}
{{--var action = route('notifications.mark-as-read', {'notification': notifId});--}}
{{--axios--}}
{{--.post(action.toString())--}}
{{--.then(function (res) {--}}
{{--if (res.data.success) {--}}
{{--$parent--}}
{{--.removeClass('card-custom-primary')--}}
{{--.addClass('card-custom-secondary');--}}
{{--$self.remove();--}}
{{--} else {--}}
{{--toastr.error('Could not delete.');--}}
{{--}--}}
{{--})--}}
{{--.catch(function (e) {--}}
{{--console.log(e);--}}
{{--toastr.error('Error.');--}}
{{--})--}}
{{--.then(function () {--}}
{{--$self.prop('disabled.false');--}}
{{--});--}}
{{--}--}}
{{--</script>--}}
{{--@endsection--}}