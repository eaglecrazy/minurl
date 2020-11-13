<?php

namespace App\Http\Controllers;

use Hashids\Hashids;
use Illuminate\Http\Request;
use App\UrlData;
use Illuminate\Support\Facades\DB;

class СhartsController extends Controller
{
    public function stat($uri)
    {
        $hashids = new Hashids('urlsalt#', 4, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $id = $hashids->decode($uri);
        if (!count($id)) {
            abort(404);
        }
        $id = $id[0];
        $browsers = DB::select('select browser, COUNT(*) as count from urlDatas where linkId = :id GROUP BY browser', ['id' => $id]);
        $countries = DB::select('select country, COUNT(*) as count from urlDatas where linkId = :id GROUP BY country', ['id' => $id]);
        $cities = DB::select('select city, COUNT(*) as count from urlDatas where linkId = :id GROUP BY city', ['id' => $id]);
        $regions = DB::select('select region, COUNT(*) as count from urlDatas where linkId = :id GROUP BY region', ['id' => $id]);
        return view('chart', ['regions' => $regions, 'browsers' => $browsers, 'countries' => $countries, 'cities' => $cities,]);
    }
}
