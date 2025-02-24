<?php

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\Attribute\Route;

return function (RoutingConfigurator $routes): void {

    $routes->import(
        "../../Actions/",
        class_exists(Route::class) ? 'attribute' : 'annotation'
    );
};