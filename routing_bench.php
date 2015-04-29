<?php

require 'vendor/autoload.php';

function callback()
{
}

function handleSingleArgument($arg)
{
}

$options = [];

$nRoutes  = 100;
$nMatches = 30000;

$router = new \Baklava\Router\Router();

for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++) {
    $router->addRoute('GET', '/' . $str . '/:arg', 'handleSingleArgument');
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

// unknown route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $router->dispatch('GET', '/foobar/bar');
}
printf("Baklava unknown route: %f\n", microtime(true) - $startTime);

echo "\n-----\n\n";

$nRoutes  = 100;
$nArgs    = 9;
$nMatches = 20000;

function handleNineArguments($a, $b, $c, $d, $e, $f, $g, $h, $i)
{
}

$router = new \Baklava\Router\Router();
for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++) {
    $router->addRoute('GET', '/' . $str, 'handleNineArguments');
    $lastStr = $str;
}

$args = implode('/', array_map(function ($i) {
    return ':arg' . $i;
}, range(1, $nArgs)));

// first route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $router->dispatch('GET', '/a/' . $args);
}
printf("Baklava first route: %f\n", microtime(true) - $startTime);

// last route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $router->dispatch('GET', '/' . $lastStr . '/' . $args);
}
printf("Baklava last route: %f\n", microtime(true) - $startTime);

// unknown route
$startTime = microtime(true);
for ($i = 0; $i < $nMatches; $i++) {
    $res = $router->dispatch('GET', '/foobar/' . $args);
}
printf("Baklava unknown route: %f\n", microtime(true) - $startTime);