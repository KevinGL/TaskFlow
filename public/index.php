<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    $env = $_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? 'prod';
    $debug = (bool) ($_SERVER['APP_DEBUG'] ?? $_ENV['APP_DEBUG'] ?? false);

    return new Kernel($env, $debug);
};
