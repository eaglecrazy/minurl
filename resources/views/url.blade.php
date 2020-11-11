<!DOCTYPE html>
{{--Laravel 7.12--}}
{{--Bootstrap v4.5.3--}}

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>URL minifier</title>
    {{--CSS--}}
    <link href="css/app.css" rel="stylesheet">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    {{--Favicon--}}
    <link rel="icon" type="image/ico" href="favicon.ico" sizes="16x16">
    <link rel="icon" type="image/ico" href="favicon.ico" sizes="32x32">
    <link rel="icon" type="image/ico" href="favicon.ico" sizes="96x96">
</head>
<body>
<div class="flex-center position-ref full-height">
    <div class="flex-column">
        <div class="content">
            <div class="title m-b-md">URL minifier</div>
        </div>
        <form class="content" action="{{ route('getUrl') }}" method="post">
            @csrf
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Link" aria-label="Link" aria-describedby="basic-addon2" name="url">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Get Short Link</button>
                </div>
            </div>
            @if ($errors->has('url'))
                <span class="invalid-feedback"><strong>{{ $errors->first('url') }}</strong></span>
            @endif
        </form>
    </div>
</div>
</body>
</html>
