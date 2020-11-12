<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Url;
use Hashids\Hashids;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use UAParser\Parser;


class UrlController extends Controller
{
    public function home()
    {
        $date = Carbon::now()->toDateString();
        $time = Carbon::now()
            ->addHours(3)
            ->addMinutes(5)
            ->toTimeString('minutes');
        $minTime = $date . 'T' . $time;
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
        if (empty($url)) {

            //если включено ограничение времени жизни
            $expireDateTime = null;
            if ($urlRequest['datetime']) {
                $now = Carbon::now()->addHours(3);
                $expireDateTime = new Carbon($urlRequest['datetime']);
                //введено неправильное время
                if ($now->diffInMinutes($expireDateTime, false) <= 0) {
                    return redirect(route('home'))
                        ->withInput()
                        ->withErrors(['datetime' => 'Wrong date or time.']);
                }
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
        $url = Url::find($id[0]);

        //проверка на время жизни ссылки
        if (isset($url['expire'])) {
            $now = Carbon::now()->addHours(3);
            $expire = new Carbon($url['expire']);
            //если ссылка истекла удалим её
            if ($now->diffInMinutes($expire, false) <= 0) {
                $url->delete();
                abort(419);
            }
        }
        $url = 'http://' . $url['url'];

        //СОБЕРЁМ СТАТИСТИКУ
        $browser = $this->parseUserAgent($_SERVER['HTTP_USER_AGENT']);

        $ip = $this->get_ip();
        //ТЕСТОВЫЙ АДРЕС
        $ip = '2.136.0.255';
        $place = $this->get_place($ip);

        return redirect()->away(url($url));
    }

    function get_ip()
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
            'ip' => $ip,
        ];
    }

    private function parseUserAgent($user)
    {
        $parser = Parser::create();
        $result = $parser->parse($user);
        if(!isset($result->ua->family))
            return null;
        return $result->ua->family;
    }
}
