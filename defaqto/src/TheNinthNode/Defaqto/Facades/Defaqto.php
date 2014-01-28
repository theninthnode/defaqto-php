<?php

namespace TheNinthNode\Defaqto\Facades;

use Illuminate\Support\Facades\Facade;

class Defaqto extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'defaqto';
    }
}
