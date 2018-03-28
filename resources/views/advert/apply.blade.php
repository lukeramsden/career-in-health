@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="edit-profile-container form-container has-top-bar">
            <div class="row first-row">
                <div class="col-md-7 form-section">
                    <h1>Apply for Job</h1>
                </div>
                <div class="col-md-5 help-section">
                    <h1>Help</h1>
                </div>
            </div>

            <form action="{{ route('advert.apply.store', ['advert' => $advert]) }}" method="post">
                {{ csrf_field() }}
    
                <div class="row">
                    <div class="col-md-7 form-section">
                        <div class="form-group">
                            <label>Custom Cover Letter</label>
                            <textarea class="form-control {{ $errors->has('custom_cover_letter') ? 'is-invalid' : '' }}" maxlength="3000" placeholder="Cover Letter (max 3000 characters)" name="custom_cover_letter" rows="5">{{ old('custom_cover_letter') }}</textarea>
                        
                            @if ($errors->has('custom_cover_letter'))
                                <div class="invalid-feedback">{{ $errors->first('custom_cover_letter') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-5 help-section">
                        <p>If blank we will use our default cover letter</p>
                    </div>
                </div>
                
                <div class='row'>
                    <div class='col-md-7 form-section'>
                        <button class='btn btn-action btn-big'>Apply</button>
                    </div>
                    <div class='col-md-5 help-section'></div>
                </div>
            </form>
        </div>
    </div>
@endsection

