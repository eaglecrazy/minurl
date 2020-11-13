<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UrlData extends Model
{
    protected $fillable = ['linkId', 'browser', 'country', 'city', 'region', 'ip'];
    protected $table = 'urlDatas';

    public static function getStatisticsData($id)
    {
        $browsers = DB::select('select browser, COUNT(*) as count from urlDatas where linkId = :id GROUP BY browser', ['id' => $id]);
        $countries = DB::select('select country, COUNT(*) as count from urlDatas where linkId = :id GROUP BY country', ['id' => $id]);
        $cities = DB::select('select city, COUNT(*) as count from urlDatas where linkId = :id GROUP BY city', ['id' => $id]);
        $regions = DB::select('select region, COUNT(*) as count from urlDatas where linkId = :id GROUP BY region', ['id' => $id]);
        return ['regions' => $regions, 'browsers' => $browsers, 'countries' => $countries, 'cities' => $cities,];
    }
}
