<!DOCTYPE html>
{{--Laravel 7.12--}}
{{--Bootstrap v4.5.3--}}

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Statistics</title>
    {{--CSS--}}
    <link href="../css/app.css" rel="stylesheet">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    {{--Favicon--}}
    <link rel="icon" type="image/ico" href="../favicon.ico" sizes="16x16">
    <link rel="icon" type="image/ico" href="../favicon.ico" sizes="32x32">
    <link rel="icon" type="image/ico" href="../favicon.ico" sizes="96x96">
    {{--JS--}}
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages': ['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawBrowsers);
        google.charts.setOnLoadCallback(drawCountries);
        google.charts.setOnLoadCallback(drawRegions);
        google.charts.setOnLoadCallback(drawCities);

        function drawBrowsers() {
            let browsers = google.visualization.arrayToDataTable([
                ['Browsers', 'Count'],
                    @foreach($browsers as $item)['{{ $item->browser }}', {{ $item->count }}],@endforeach]);
            let options = {title: 'Browser statistics'};
            let chart = new google.visualization.PieChart(document.getElementById('browsers'));
            chart.draw(browsers, options);
        }

        function drawCountries() {
            let countries = google.visualization.arrayToDataTable([
                ['Countries', 'Clicks'],
                    @foreach($countries as $item)['{{ $item->country }}', {{ $item->count }}],@endforeach]);
            let options = {title: 'Link conversion statistics by country'};
            let chart = new google.charts.Bar(document.getElementById('countries'));
            chart.draw(countries, google.charts.Bar.convertOptions(options));
        }

        function drawRegions() {
            let regions = google.visualization.arrayToDataTable([
                ['Regions', 'Clicks'],
                    @foreach($regions as $item)['{{ $item->region }}', {{ $item->count }}],@endforeach]);
            let options = {title: 'Link conversion statistics by region'};
            let chart = new google.charts.Bar(document.getElementById('regions'));
            chart.draw(regions, google.charts.Bar.convertOptions(options));
        }

        function drawCities() {
            let regions = google.visualization.arrayToDataTable([
                ['Cities', 'Clicks'],
                    @foreach($cities as $item)['{{ $item->city }}', {{ $item->count }}],@endforeach]);
            let options = {title: 'Link conversion statistics by city'};
            let chart = new google.charts.Bar(document.getElementById('cities'));
            chart.draw(regions, google.charts.Bar.convertOptions(options));
        }
    </script>
</head>
<body>
<div class="d-flex flex-column align-items-center">
    <div class="title mt-3">Link statistics:</div>
    <div class="statistics" id="browsers" style="width: 800px; height: 500px;"></div>
    <div class="mt-3 statistics" id="countries" style="width: 800px; height: 500px;"></div>
    <div class="mt-3 statistics" id="regions" style="width: 800px; height: 500px;"></div>
    <div class="mt-3 mb-5 statistics" id="cities" style="width: 800px; height: 500px;"></div>
</div>
</body>
</html>
