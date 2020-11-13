<?php

namespace App\Http\Controllers;

use App\Services\HashService;
use App\Url;
use App\UrlData;

class ChartsController extends Controller
{
    public function stat($uri)
    {
        $hash = new HashService();
        $id = $hash->decode($uri);
        $urlText = Url::getUrl($id[0]);
        //если ссылка просрочена
        if (empty($urlText))
            abort(419);
        if (!count($id)) {
            abort(404);
        }
        return view('chart', UrlData::getStatisticsData($id[0]));
    }
}
