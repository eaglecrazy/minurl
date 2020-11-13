<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Services\DateTimeService;

class Url extends Model
{
    protected $fillable = ['url', 'expire'];

    public static function storeUrl($urlText, $urlTimeCheckBox, $urlTime)
    {
        $urlText = static::trimUrl($urlText);
        //поищем этот url в БД
        $url = static::where('url', $urlText)->first();
        if (isset($url))
            return $url;
        //не нашли
        //если в реквесте было указано время
        if ($urlTimeCheckBox === 'on') {
            $time = new DateTimeService();
            if ($time->isNotCorrectTime($urlTime)) {
                return null;
            }
            $expireDateTime = $time->getExpireDateTime($urlTime);
        } else {
            $expireDateTime = null;
        }
        return static::create(['url' => $urlText, 'expire' => $expireDateTime]);
    }


    private static function trimUrl($urlText)
    {
        $urlText = Str::replaceFirst('http://', '', $urlText);
        $urlText = Str::replaceFirst('https://', '', $urlText);
        if ($urlText[Str::length($urlText) - 1] == '/') {
            $urlText = Str::replaceLast('/', '', $urlText);
        }
        return $urlText;
    }

    public static function getUrl($id)
    {
        $url = static::find($id);
        if (empty($url))
            return null;
        //проверка на время жизни ссылки
        $time = new DateTimeService();
        if (isset($url['expire']) && $time->linkIsExpired($url['expire'])) {
            UrlData::where('linkId', $url->id)->delete();
            $url->delete();
            return null;
        }
        return 'http://' . $url['url'];
    }
}
