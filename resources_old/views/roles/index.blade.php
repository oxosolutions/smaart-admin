<?php
    $collection = Route::getRoutes();
    $routes = [];
    foreach($collection as $route) {
        $routes[] = $route->getPath();
    }

    dd($routes);
?>
