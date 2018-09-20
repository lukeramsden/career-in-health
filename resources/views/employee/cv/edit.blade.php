@extends('layouts.app')
@section('content')
    <div class="container">
        <div id="vue-cv-builder" class="mt-5">
            <cv-builder :model="data.model" :schemas="data.schemas"></cv-builder>
        </div>
    </div>
@endsection

@push('pre-scripts')
    <script>
        const schemas = [
            {
                name: 'preferences',
                label: 'Desired Job',
                sublabel: 'Help us match you with your next job',
                url: '{{ route('cv.preferences.update') }}',
                multiple: false,
                fields: [
                    {
                        type: 'dropdown',
                        handler: 'select2',
                        label: 'Job Role',
                        model: 'job_role',
                        data: [
                                @foreach(\App\JobRole::all() ?? [] as $job)
                            {
                                name: '{{ $job->name }}',
                                value: '{{ $job->id }}',
                            },
                            @endforeach
                        ],
                    },
                    {
                        type: 'dropdown',
                        handler: 'select2',
                        label: 'Setting',
                        model: 'setting',
                        data: [
                                @foreach(\App\JobListing::$settings ?? [] as $id => $setting)
                            {
                                name: '{{ $setting }}',
                                value: '{{ $id }}',
                            },
                            @endforeach
                        ]
                    },
                    {
                        type: 'dropdown',
                        handler: 'select2',
                        label: 'Type',
                        model: 'type',
                        data: [
                                @foreach(\App\JobListing::$types ?? [] as $id => $type)
                            {
                                name: '{{ $type }}',
                                value: '{{ $id }}',
                            },
                            @endforeach
                        ]
                    },
                    {
                        type: 'checkbox',
                        label: 'I am willing to relocate',
                        model: 'willing_to_relocate',
                    },
                ],
            },
            {
                name: 'education',
                label: 'Education',
                url: '{{ route('cv.education.store') }}',
                multiple: true,
                fields: [
                    {
                        type: 'input',
                        inputType: 'text',
                        label: 'Degree',
                        model: 'degree',
                        helpText: 'e.g. Diploma, Bachelor\'s, PhD.',
                        required: true,
                        max: 150,
                    },
                    {
                        type: 'input',
                        inputType: 'text',
                        label: 'College or University',
                        model: 'school_name',
                        required: true,
                        max: 150,
                    },
                    {
                        type: 'input',
                        inputType: 'text',
                        label: 'Field Of Study',
                        model: 'field_of_study',
                        helpText: 'e.g. Management, Nursing, Psychology.',
                        required: true,
                        max: 150,
                    },
                    {
                        type: 'input',
                        inputType: 'text',
                        label: 'City',
                        model: 'location',
                        helpText: 'e.g. London, Manchester, Birmingham.',
                        max: 150,
                        required: true,
                    },
                    {
                        type: 'datePicker',
                        multiple: true,
                        models: [
                            {
                                model: 'start_date',
                                label: 'Start Date',
                                required: true,
                                inline: false,
                                options: {
                                    format: 'MM yyyy',
                                    minViewMode: 1,
                                    maxViewMode: 2,
                                },
                            },
                            {
                                model: 'end_date',
                                label: 'End Date (or expected graduation date)',
                                inline: false,
                                options: {
                                    format: 'MM yyyy',
                                    minViewMode: 1,
                                    maxViewMode: 2,
                                    clearBtn: true,
                                },
                            },
                        ],
                    },
                ],
            },
            {
                name: 'work_experience',
                label: 'Work Experience',
                url: '{{ route('cv.workExperience.store') }}',
                multiple: true,
                fields: [
                    {
                        type: 'input',
                        inputType: 'text',
                        label: 'Job Title',
                        model: 'job_title',
                        helpText: 'e.g. Manager, Senior Nurse, Midwife.',
                        required: true,
                        max: 150,
                    },
                    {
                        type: 'input',
                        inputType: 'text',
                        label: 'Company Name',
                        model: 'company_name',
                        helpText: 'Name of the company.',
                        required: true,
                        max: 150,
                    },
                    {
                        type: 'input',
                        inputType: 'area',
                        label: 'Description',
                        model: 'description',
                        helpText: 'A small description of your time here.',
                        required: false,
                        max: 500,
                    },
                    {
                        type: 'input',
                        inputType: 'text',
                        label: 'City',
                        model: 'location',
                        helpText: 'e.g. London, Manchester, Birmingham.',
                        required: true,
                        max: 150,
                    },
                    {
                        type: 'datePicker',
                        multiple: true,
                        models: [
                            {
                                model: 'start_date',
                                label: 'Start Date',
                                required: true,
                                inline: false,
                                options: {
                                    format: 'MM yyyy',
                                    minViewMode: 1,
                                    maxViewMode: 2,
                                },
                            },
                            {
                                model: 'end_date',
                                label: 'End Date (leave empty if you still work here)',
                                inline: false,
                                options: {
                                    format: 'MM yyyy',
                                    minViewMode: 1,
                                    maxViewMode: 2,
                                    clearBtn: true,
                                },
                            },
                        ],
                    },
                ],
            },
            {
                name: 'certifications',
                label: 'Certifications and Licenses',
                url: '{{ route('cv.certifications.store') }}',
                multiple: true,
                fields: [
                    {
                        type: 'input',
                        inputType: 'text',
                        label: 'Title',
                        model: 'title',
                        required: true,
                        max: 150,
                    },
                    {
                        type: 'datePicker',
                        multiple: true,
                        models: [
                            {
                                model: 'start_date',
                                label: 'Start Date',
                                required: true,
                                inline: false,
                                options: {
                                    format: 'MM yyyy',
                                    minViewMode: 1,
                                    maxViewMode: 2,
                                },
                            },
                            {
                                model: 'end_date',
                                label: 'Expiration Date (leave empty if this certification or license does not expire)',
                                inline: false,
                                options: {
                                    format: 'MM yyyy',
                                    minViewMode: 1,
                                    maxViewMode: 2,
                                    clearBtn: true,
                                },
                            },
                        ],
                    },
                    {
                        type: 'input',
                        inputType: 'area',
                        label: 'Description',
                        model: 'description',
                        required: false,
                        max: 500,
                    },
                    {
                        type: 'file',
                        model: 'file',
                        if: (field, model) => model.new || false,
                        label: 'PDF or Picture of Certification/License',
                        helpText: '(.pdf, .png, .jpg, .jpeg)',
                        required: true,
                        max: 1024,
                        fileTypes: ['pdf', 'png', 'jpg']
                    },
                ]
            },
        ];
        
        window.data.cvBuilder = {
            model: {
                @php($cv = Auth::user()->userable->cv)
                preferences: {!! optional($cv->preferences)->toJson() ?? '{}'!!},
                education: {!! optional($cv->education)->toJson() ?? '[]'!!},
                work_experience: {!! optional($cv->workExperience)->toJson() ?? '[]'!!},
                certifications: {!! optional($cv->certifications)->toJson() ?? '[]'!!},
            },
            schemas: schemas,
        };
    </script>
@endpush
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        toastr.options = {
            "closeButton": true,
            "newestOnTop": false,
            "positionClass": "toast-top-right",
            "progressBar": true,
        };
    </script>
    <script src="{{ mix('js/cv-builder.js') }}"></script>
@endsection
@section('stylesheet')
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link href="{{ mix('css/lity.css') }}" rel="stylesheet">
@endsection
