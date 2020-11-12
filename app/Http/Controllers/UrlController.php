<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Url;
use Hashids\Hashids;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;


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
        if(isset($url['expire'])){
            $now = Carbon::now()->addHours(3);
            $expire = new Carbon($url['expire']);
            //если ссылка истекла удалим её
            if($now->diffInMinutes($expire, false) <= 0){
                $url->delete();
                abort(419);
            }
        }
        $url = 'http://' . $url['url'];

//        dd($_SERVER['HTTP_USER_AGENT']);

        return redirect()->away(url($url));
    }
}
