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



        dd($regions);





//        $data = UrlData::where('linkId', $id)
//            ->select('browser', 'country', 'city', 'region')
//            ->get();

        //тут 100% можно сделать лучше при помощи коллекций, но не знаю как
//        $browsers = [];
//        $countryes = [];
//        $cityes = [];
//        $regions = [];
//
//        foreach ($data as $item){
//            count($item['browser'], $browsers);
////            if (array_key_exists($item['browser'], $browsers)) {
////                $browsers[$item['browser']]++;
////            } else {
////                $browsers[$item['browser']] = 1;
////            }
//        }
    }


//
//    private function count($key, $array){
//        if (array_key_exists($key, $array))
//            $array[$key]++;
//        else
//            $array[$key] = 1;
//    }
}
