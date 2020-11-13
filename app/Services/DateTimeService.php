<?php


namespace App\Services;


use Illuminate\Support\Carbon;

class DateTimeService
{
    public function now()
    {
        return Carbon::now();
    }

    public function nowPlusFiveMinutesString()
    {
        return $this
            ->now()
            ->addMinutes(5)
            ->toTimeString('minutes');
    }

    public function nowDateString()
    {
        return $this
            ->now()
            ->toDateString();
    }

    public function datetimeLocalString()
    {
        return $minTime = $this->nowDateString() . 'T' . $this->nowPlusFiveMinutesString();
    }

    //валидация времени
    public function isNotCorrectTime($dateTime)
    {
        $now = $this->now();
        //если введено неправильное время true
        if ($now->diffInMinutes(new Carbon($dateTime), false) <= 0)
            return true;
        return false;
    }

    public function getExpireDateTime($dateTime)
    {
        return new Carbon($dateTime);
    }

    public function linkIsExpired($dateTime)
    {
        $now = $this->now();
        $expire = new Carbon($dateTime);
        if ($now->diffInMinutes($expire, false) <= 0)
            return true;
        return false;
    }
}
