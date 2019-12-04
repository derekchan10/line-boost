<?php
namespace T8891\Iris\Facades;
use Illuminate\Support\Facades\Facade;
class Iris extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Iris';
    }
}