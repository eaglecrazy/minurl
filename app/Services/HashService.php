<?php


namespace App\Services;


use Hashids\Hashids;

class HashService
{
    private $salt = 'urlsalt#';
    private $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private $hash_length = 4;
    private $hash;

    public function __construct()
    {
        $this->hash = new Hashids($this->salt, $this->hash_length, $this->alphabet);
    }
}
