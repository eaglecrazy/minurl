<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UrlData extends Model
{
    protected $fillable = ['linkId', 'browser', 'country', 'city', 'region', 'ip'];
    protected $table = 'urlDatas';
}
