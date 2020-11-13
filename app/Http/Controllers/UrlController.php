<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Services\StatisticsService;
use App\Url;
use App\Services\DateTimeService;
use App\Services\HashService;


class UrlController extends Controller
{
    public function home()
    {
        //минимальное время которое можно назначить для жизни ссылки
        $time = new DateTimeService();
        $minTime = $time->datetimeLocalString();
        return view('url', ['mintime' => $minTime]);
    }

    public function geturl(UrlRequest $urlRequest)
    {
        $url = Url::storeUrl($urlRequest['url'], $urlRequest['expire'], $urlRequest['datetime']);
        if (!$url)
            return redirect(route('home'))
                ->withInput()
                ->withErrors(['datetime' => 'Wrong date or time.']);

        $hash = new HashService();
        $hash = $hash->encode($url['id']);
        return view('short', ['url' => $hash]);
    }

    public function redirect($uri)
    {
        $hash = new HashService();
        $id = $hash->decode($uri);
        if (!count($id)) {
            abort(404);
        }
        $urlText = Url::getUrl($id[0]);
        //если ссылка просрочена
        if (empty($urlText))
            abort(419);

        //соберём статистику
        new StatisticsService($id[0], true);
        //new StatisticsService($id[0], false);

        return redirect()->away(url($urlText));
    }
}
