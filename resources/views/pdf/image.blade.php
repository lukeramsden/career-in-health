<!DOCTYPE html>
<html>
<head>
    <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
    <style>
        div {
            page-break-after: avoid;
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div>
        <img src="{{ $src }}" style="width:100%;height:auto;margin:0;">
    </div>
</body>
</html>
