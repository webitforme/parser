<?php

namespace WebIt4MeTest;

class Bootstrap
{
    public static function run($autoLoader)
    {
        include $autoLoader;
    }
}

Bootstrap::run(__DIR__ . '/../vendor/autoload.php');