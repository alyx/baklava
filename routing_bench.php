<?php

#error_reporting(E_ALL);

#require __DIR__ . '/FastRoute/src/bootstrap.php';
include_once('src/router.php');

function callback() {}

$options = [];

$nRoutes = 100;
$nMatches = 30000;

//$router = FastRoute\simpleDispatcher(function($router) use ($nRoutes, &$lastStr) {
    //for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++) {
        //$router->addRoute('GET', '/' . $str . '/{arg}', 'handler' . $i);
        //$lastStr = $str;
    //}
//}, $options);
function handle1arg($arg) { }
$router = new Router\Router();
for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++)
{
    $router->addRoute('GET', '/' . $str, 'handle1arg');
    $lastStr = $str;
}

// first route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $router->dispatch('GET', '/a/foo');
}
printf("Baklava first route: %f\n", microtime(true) - $startTime);
//var_dump($res);

// last route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $router->dispatch('GET', '/' . $lastStr . '/foo');
}
printf("Baklava last route: %f\n", microtime(true) - $startTime);
//var_dump($res);

// unknown route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $router->dispatch('GET', '/foobar/bar');
}
printf("Baklava unknown route: %f\n", microtime(true) - $startTime);
//var_dump($res);

echo "\n-----\n\n";


$nRoutes = 100;
$nArgs = 9;
$nMatches = 20000;

//$args = implode('/', array_map(function($i) { return "{arg$i}"; }, range(1, $nArgs)));
function handle9args($a, $b, $c, $d, $e, $f, $g, $h, $i) { }

//$router = FastRoute\simpleDispatcher(function($router) use($nRoutes, $args, &$lastStr) {
    //for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++) {
        //$router->addRoute('GET', '/' . $str . '/' . $args, 'handler' . $i);
        //$lastStr = $str;
    //}
//}, $options);
//
$router = new Router\Router();
for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++) {
    $router->addRoute('GET', '/' . $str, handle9args);
    $lastStr = $str;
}

// first route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $router->dispatch('GET', '/a/' . $args);
}
printf("Baklava first route: %f\n", microtime(true) - $startTime);
//var_dump($res);

// last route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $router->dispatch('GET', '/' . $lastStr . '/' . $args);
}
printf("Baklava last route: %f\n", microtime(true) - $startTime);
//var_dump($res);

// unknown route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $router->dispatch('GET', '/foobar/' . $args);
}
printf("Baklava unknown route: %f\n", microtime(true) - $startTime);
//var_dump($res);
