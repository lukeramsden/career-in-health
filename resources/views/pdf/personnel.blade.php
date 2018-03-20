<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $profile->first_name . ' ' . $profile->last_name }}</title>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container-fluid">
        <h2 style="text-align: center">{{ $profile->first_name . ' ' . $profile->last_name }}</h2>
        <h5 style="text-align: center">{{ $profile->headline }}</h5>
        <h5 style="text-align: center">{{ $profile->location }}</h5>
        <p style="text-align: center">{{ $profile->description }}</p>

        <ul class="list-group">
            @foreach($profile->jobTypes as $job)
                <li class="list-group-item">{{ $job->name }}</li>
            @endforeach
        </ul>
        
        <hr>
        <p><b>Work Experience</b></p>
        <hr>

        @foreach($profile->work as $work)
            <p>{{ $work->job_title }}</p>
            <p>{{ $work->company_name }}</p>
            <p>{{ $work->start_date }}</p>
            @isset($work->end_date)
                <p>{{ $work->end_date }}</p>
            @endisset

            @empty($work->end_date)
                <p><b>Currently Working Here</b></p>
            @endempty
            
            @foreach($work->references as $reference)
                <p><em>{{ $reference->person_name }}</em> at <em>{{ $reference->person_company }}</em></p>
            @endforeach
            <hr>
        @endforeach

        <hr>
        <p><b>References</b></p>
        <hr>
        
        @foreach($profile->references as $reference)
            <p>{{ $reference->person_name }}</p>
            <p>{{ $reference->person_company }}</p>
            <p>{{ $reference->person_relation }}</p>
            <p>{{ $reference->person_contact }}</p>
            @if(isset($reference->work))
                <p><em>{{ $reference->work->job_title }}</em> at <em>{{ $reference->work->company_name }}</em></p>
            @endif
            <hr>
        @endforeach

        <hr>
        <p><b>Certifications</b></p>
        <hr>
        
        @foreach($profile->certifications as $certification)
            <a>{{ $certification->name }}</a>
        @endforeach
    </div>
</body>
</html>