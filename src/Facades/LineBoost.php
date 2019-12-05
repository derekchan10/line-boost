<?php
namespace T8891\LineBoost\Facades;
use Illuminate\Support\Facades\Facade;
class LineBoost extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'LineBoost';
    }
}