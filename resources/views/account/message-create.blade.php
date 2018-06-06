@extends('layouts.app')
@section('content')
    <div class="container mt-lg-5">
        <div class="card card-custom mx-lg-5">
            <div class="card-header">
                @isset($replyTo)
                    <b>From:</b> <a href="{{ route('company.show', [$replyTo->advert->company]) }}">{{ $replyTo->advert->company->name }}</a>
                    <br>
                    <b>Advert:</b> <a href="{{ route('advert.show', [$replyTo->advert]) }}">{{ $replyTo->advert->title }}</a>
                    <br>
                    <small class="text-muted">{{ $replyTo->body }}</small>
                
                    @if(\App\AdvertApplication::hasApplied(Auth::user(), $replyTo->advert))
                        <hr>
                        <b>You have applied to this advert!</b>
                        <br>
                        <small class="text-muted">
                            {{ str_limit(
                                \App\AdvertApplication::getApplication(
                                    Auth::user(),
                                    $replyTo->advert
                                )->custom_cover_letter ?? 'No cover letter', 100) }}
                        </small>
                    @endif
                @elseif(isset($advert))
                    <b>To:</b> <a href="{{ route('company.show', [$advert->company]) }}">{{ $advert->company->name }}</a>
                    <br>
                    <b>Advert:</b> <a href="{{ route('advert.show', [$advert]) }}">{{ $advert->title }}</a>
                    
                    @if(\App\AdvertApplication::hasApplied(Auth::user(), $advert))
                        <hr>
                        <b>You have applied to this advert!</b>
                        <br>
                        <small class="text-muted">
                            {{ str_limit(
                                \App\AdvertApplication::getApplication(
                                    Auth::user(),
                                    $advert
                                )->custom_cover_letter ?? 'No cover letter', 100) }}
                        </small>
                    @endif
                @endisset
            </div>
            <div class="card-body">
                <form
                @isset($replyTo)
                action="{{ route('account.private-message.reply', [$replyTo]) }}"
                @elseif(isset($advert))
                action="{{ route('account.private-message.new', [$advert]) }}"
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