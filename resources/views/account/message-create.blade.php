@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="card card-custom mx-lg-5 mt-5">
            @isset($replyTo)
                <div class="card-header">
                    <b>From:</b> <a href="{{ route('company.show', [$replyTo->advert->company]) }}">{{ $replyTo->advert->company->name }}</a>
                    <br>
                    <b>Advert:</b> <a href="{{ route('advert.show', [$replyTo->advert]) }}">{{ $replyTo->advert->title }}</a>
                    <br>
                    <small class="text-muted">{{ $replyTo->body }}</small>
                </div>
            @else
                <div class="card-header">
                    <b>To:</b> <a href="javascript:">somebody</a>
                </div>
            @endisset
            <div class="card-body">
                <form
                @isset($replyTo)
                action="{{ route('account.private-message.reply', [$replyTo]) }}"
                @else
                action=""
                @endisset
                method="post">
                    {{ csrf_field() }}
                    
                    <div class="form-group">
                        <label for="inputBody">Message</label>
                        <textarea
                        class="form-control {{ $errors->has('body') ? 'is-invalid' : '' }}"
                        name="body"
                        id="inputBody"
                        rows="10"
                        maxlength="1000">{{ old('body') }}</textarea>
                        
                        @if ($errors->has('body'))
                            <div class="invalid-feedback">{{ $errors->first('body') }}</div>
                        @endif
                    </div>
                    
                    <button type="submit" class="btn btn-action px-4">Send</button>
                </form>
            </div>
        </div>
    </div>
@endsection