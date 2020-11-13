<?php


namespace App\Services;

use App\UrlData;
use UAParser\Parser;

class StatisticsService
{
    public function __construct($id, $dev)
    {
        $browser = $this->parseUserAgent($_SERVER['HTTP_USER_AGENT']);
        if (empty($browser))
            return;
        $ip = $this->getIp();
        if (empty($ip))
            return;
        //ТЕСТОВЫЕ АДРЕСА ДЛЯ РАБОТЫ В ЛОКАЛЬНОМ ОКРУЖЕНИИ
        if ($dev) {
            $ips = ['2.136.0.255', '185.81.67.98', '77.222.113.80', '66.249.89.243', '2.132.171.161', '103.253.24.37'];
            $ip = $ips[rand(0, 5)];
        }
        $place = $this->getPlace($ip);
        if (empty($place))
            return;

        UrlData::create([
            'linkId' => $id,
            'ip' => $ip,
            'browser' => $browser,
            'country' => $place['country'],
            'city' => $place['city'],
            'region' => $place['region'],
        ]);
    }

    private function parseUserAgent($user)
    {
        $parser = Parser::create();
        $result = $parser->parse($user);
        if (!isset($result->ua->family))
            return null;
        return $result->ua->family;
    }

    private function getPlace($ip)
    {
        $myApiKey = 'HWorTrJ8I9hIHV90JZQgmf9Sy6PxyAcIyzgD3M2c';
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


    private function getIp()
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
}
