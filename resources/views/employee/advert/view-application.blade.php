@extends('layouts.app')
@section('content')
    <div class="container mt-lg-3">
        <div class="card card-custom">
            <div class="card-body">
                <form action="{{ route('job-listing.application.update', [$application]) }}" method="post">
                    {{ csrf_field() }}
                    
                    <div class="form-group">
                        <label>Custom Cover Letter</label>
                        <textarea class="form-control {{ $errors->has('custom_cover_letter') ? 'is-invalid' : '' }}" maxlength="3000" placeholder="Cover Letter (max 3000 characters)" name="custom_cover_letter" rows="5">{{ old('custom_cover_letter', $application->custom_cover_letter) }}</textarea>
                        <small class="form-text text-muted"></small>
                        @if ($errors->has('custom_cover_letter'))
                            <div class="invalid-feedback">{{ $errors->first('custom_cover_letter') }}</div>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <p>
                            Status:
                            <span class="text-action">
                                {{ \App\JobListingApplication::$statuses[$item->status ?? 0] }}
                            </span>
                        </p>
                    </div>
                    
                    <div class="form-group">
                        <button class="btn btn-action btn-big px-5">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection