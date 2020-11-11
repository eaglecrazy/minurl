<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    public function geturl(UrlRequest $url){
        echo $url->input('url');
        //
    }
}
