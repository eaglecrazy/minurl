<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Url;
use Illuminate\Http\Request;
use Hashids\Hashids;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;


class UrlController extends Controller
{
    public function geturl(UrlRequest $urlRrequest)
    {
        $hashids = new Hashids('urlsalt#', 4, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

        //убираем лишние символы из ссылки
        $urlText = $urlRrequest->input('url');
        $urlText = Str::replaceFirst('http://', '', $urlText);
        $urlText = Str::replaceFirst('https://', '', $urlText);
        if ($urlText[Str::length($urlText) - 1] == '/') {
            $urlText = Str::replaceLast('/', '', $urlText);
        }

        //поищем этот url
        $url = Url::where('url', $urlText)->first();
        if (empty($url)) {
            $url = Url::create(['url' => $urlText]);
        }

        $hash = $hashids->encode($url['id']);
        return view('short', ['url' => $hash]);
    }

    public function redirect($uri){
        $hashids = new Hashids('urlsalt#', 4, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $id = $hashids->decode($uri);
        if(!count($id)){
            abort(404);
        }
        $url = Url::find($id[0]);
        $url = 'http://' . $url['url'];
        return redirect()->away(url($url));
    }
}
