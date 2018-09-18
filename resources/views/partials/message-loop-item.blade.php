@set('isReceiver', $message->wasSentTo())

<div
class="card card-custom message-thread-item mb-4 {{$isReceiver?'message-thread-item-sent':'message-thread-item-received'}}"
>
    @if($isReceiver)
        @usertype('employee')
        <div class="card-header">
            <b>From:</b> {{ $message->company->name }} {!!verified_badge($message->company)!!}
        </div>
        @elseusertype('company')
        <div class="card-header"><b>From:</b> {{ $message->employee->full_name }}</div>
        @endusertype
    @else
        <div class="card-header"><b>You said...</b></div>
    @endif
    <div class="card-body">{{ $message->body }}</div>
    <div class="card-footer">{{ $message->created_at->diffForHumans() }}</div>
</div>