<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Url;
use App\UrlData;
use Hashids\Hashids;
use Illuminate\Support\Str;
use UAParser\Parser;
use App\Services\DateTimeService;
use App\Services\HashService;


class UrlController extends Controller
{
    private $time;
    private $hash;

    public function __construct()
    {
        $this->time = new DateTimeService();
        $this->hash = new DateTimeService();
    }

    public function home()
    {
        //минимальное время которое можно назначить для жизни ссылки
        $minTime = $this->time->datetimeLocalString();
        return view('url', ['mintime' => $minTime]);
    }







    public function geturl(UrlRequest $urlRequest)
    {
        $hashids = new Hashids('urlsalt#', 4, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

        //убираем лишние символы из ссылки
        $urlText = $urlRequest->input('url');
        $urlText = Str::replaceFirst('http://', '', $urlText);
        $urlText = Str::replaceFirst('https://', '', $urlText);
        if ($urlText[Str::length($urlText) - 1] == '/') {
            $urlText = Str::replaceLast('/', '', $urlText);
        }

        //поищем этот url
        $url = Url::where('url', $urlText)->first();
        //если не нашли то добавим в БД
        $expireDateTime = null;
        if (empty($url)) {
            //если в реквесте было указано время
            if ($urlRequest['expire'] === 'on') {
                $requestTime = $urlRequest['datetime'];
                //если время не прошло валидацию
                if ($this->time->isNotCorrectTime($requestTime)) {
                    return redirect(route('home'))
                        ->withInput()
                        ->withErrors(['datetime' => 'Wrong date or time.']);
                }
                //время было проверено
                $expireDateTime = $this->time->getExpireDateTime($requestTime);
            }
            $url = Url::create(['url' => $urlText, 'expire' => $expireDateTime]);
        }

        //сделаем ссылочку
        $hash = $hashids->encode($url['id']);

        return view('short', ['url' => $hash]);
    }

    public function redirect($uri)
    {
        $hashids = new Hashids('urlsalt#', 4, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $id = $hashids->decode($uri);
        if (!count($id)) {
            abort(404);
        }
        $id = $id[0];
        $url = Url::find($id);

        //проверка на время жизни ссылки
        if (isset($url['expire']) && $this->time->linkIsExpired($url['expire'])) {
            $url->delete();
            //тут нужно найти и удалить связанные данные
            //тут нужно найти и удалить связанные данные
            //тут нужно найти и удалить связанные данные
            //тут нужно найти и удалить связанные данные
            //тут нужно найти и удалить связанные данные
            abort(419);
        }

        $url = 'http://' . $url['url'];

        //СОБЕРЁМ СТАТИСТИКУ
        $this->statistics($id);
        return redirect()->away(url($url));
    }

    private function statistics($id)
    {
        $browser = $this->parseUserAgent($_SERVER['HTTP_USER_AGENT']);
        $ip = $this->get_ip();

//        ТЕСТОВЫЙ АДРЕС
        $ips = ['2.136.0.255', '185.81.67.98', '77.222.113.80', '66.249.89.243', '2.132.171.161', '103.253.24.37'];
        $ip = $ips[rand(0, 5)];

        $place = $this->get_place($ip);

        $data = UrlData::create([
            'linkId' => $id,
            'ip' => $ip,
            'browser' => $browser,
            'country' => $place['country'],
            'city' => $place['city'],
            'region' => $place['region'],
        ]);
    }

    private function get_ip()
    {
        $value = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $value = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $value = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $value = $_SERVER['REMOTE_ADDR'];
        }

        return $value;
    }

    private function get_place($ip)
    {
        $myApiKey = 'HWorTrJ8I9hIHV90JZQgmf9Sy6PxyAcIyzgD3M2c';
//        $url = 'https://ip-location.icu/api/v1/country/?apiKey='.$myApiKey.'&ip=185.81.67.98';
//        $url = 'https://ip-location.icu/api/v1/city/?apiKey='.$myApiKey.'&ip=185.81.67.98';
        $url = 'https://ip-location.icu/api/v1/city/?apiKey=' . $myApiKey . '&ip=' . $ip;
        $response = file_get_contents($url);
        $info = json_decode($response, true);

        if (isset($info['error']) || $info['country_name'] == '-') {
            return null;
        }

        return [
            'country' => $info['country_name'],
            'city' => $info['city_name'],
            'region' => $info['region_name'],
        ];
    }

    private function parseUserAgent($user)
    {
        $parser = Parser::create();
        $result = $parser->parse($user);
        if (!isset($result->ua->family))
            return null;
        return $result->ua->family;
    }
}
