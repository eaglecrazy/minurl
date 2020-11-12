<!DOCTYPE html>
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
    {{--JS--}}
    <script defer src="js/app.js"></script>
    <script defer src="js/copy.js"></script>

</head>
<body>
<div class="flex-center position-ref full-height">
    <div class="flex-column">
        <div class="content">
            <div class="title">Your short link:</div>
            <div class="input-group mb-3">
                <input type="text" class="form-control" aria-label="Link" aria-describedby="basic-addon2" id="url" value="{{ url()->route('home') . '/' . $url }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit" id="urlbtn">Copy link</button>
                </div>
            </div>
            <div class="title">Statistics:</div>
            <div class="input-group mb-3">
                <input type="text" class="form-control" aria-label="LinkStat" aria-describedby="basic-addon2" id="urlstat" value="{{ url()->route('home') . '/stat/' . $url }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit" id="urlbtn">Copy link</button>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
