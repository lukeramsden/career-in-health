<!DOCTYPE html>
<html>
<head>
    <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: white;
            color: black;
            margin: 0 auto;
            padding: 15px;
            @unless($embed || $download)
                max-width: 1000px;
                padding-top: 50px;
            @endunless
        }
        
        .section {
            margin-bottom: 2em;
        }
        
        .right {
            float: right;
        }
        
        .button {
            font-weight: 400;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
            color: #fff;
            background-color: #455782;
            border: 1px solid #455782;
            display: block;
            width: 100%;
            text-decoration: none;
            margin-top: 1rem;
        }
        
        .button:hover {
            color: #fff;
            background-color: #384669;
            border-color: #334161;
        }
        
        .page {
        	page-break-after: avoid;
        	page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="right">
        @unless($embed || $download)
            <a class="button" href="{{ route('cv.pdf.download') }}">Download</a>
        @endunless
    </div>
    <div class="container page">
        <div class="section">
            <h1 style="margin: 0;">{{ $profile->fullName() }}</h1>
            <p><b>{{ $profile->location }}</b></p>
            <p>{{ $profile->headline }}</p>
            <p>{{ $profile->description }}</p>
            <p>{{ $profile->user->email }}</p>
        </div>
        <hr>
        @php($cv = $profile->user->cv)
        @if($cv->education->count() > 0)
            <h4><em>Education</em></h4>
            
            @foreach($cv->education as $education)
                <div class="section">
                    <p><b>{{ $education->degree }} in {{ $education->field_of_study }}</b></p>
                    <p>{{ $education->school_name }} - {{ $education->location }}</p>
                    @isset($education->end_date)
                        <p>{{ $education->start_date->format('F Y') }} to {{ $education->end_date->format('F Y') }}</p>
                    @else
                        <p>Started {{ $education->start_date->format('F Y') }}</p>
                    @endisset
                </div>
            @endforeach
            <hr>
        @endif
        @if($cv->workExperience->count() > 0)
            <h4><em>Work Experience</em></h4>
            
            @foreach($cv->workExperience as $workExperience)
                <div class="section">
                    <p><b>{{ $workExperience->job_title }}</b> at <b>{{ $workExperience->company_name }}</b></p>
                    <p>{{ $workExperience->location }}</p>
                    @isset($workExperience->end_date)
                        <p>{{ $workExperience->start_date->format('F Y') }} to {{ $workExperience->end_date->format('F Y') }}</p>
                    @else
                        <p>Started {{ $workExperience->start_date->format('F Y') }}</p>
                    @endisset
                    @isset($workExperience->description)
                        <p>{!! nl2br(e($workExperience->description)) !!}</p>
                    @endisset
                </div>
            @endforeach
            <hr>
        @endif
        @if($cv->certifications->count() > 0)
            <h4><em>Certifications/Licenses</em></h4>
        
            @foreach($cv->certifications as $certification)
                <div class="section">
                    <p><b>{{ $certification->title }}</b></p>
                    @isset($certification->end_date)
                        <p>Awarded {{ $certification->start_date->format('F Y') }} - Expires {{ $certification->end_date->format('F Y') }}</p>
                    @else
                        <p>Awarded {{ $certification->start_date->format('F Y') }} (Doesn't Expire)</p>
                    @endisset
                    @isset($certification->description)
                        <p>{!! nl2br(e($certification->description)) !!}</p>
                    @endisset
                </div>
            @endforeach
            
            <hr>
        @endif
    </div>
</body>
</html>