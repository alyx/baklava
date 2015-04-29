<?php

namespace Baklava\Router;

use Baklava\Routes\Route;
use Baklava\Routes\RoutesTrie;

class Router
{
    private $routeList;

    function __construct()
    {
        $this->routeList = new RoutesTrie();
    }

    public function addRoute($method, $name, $handler)
    {
        $route = new Route($name, $handler);

        $this->routeList->add(strtolower($method . $name), $route);

        return $this;
    }

    public function search($method, $x)
    {
        return $this->routeList->prefixSearch(strtolower($method . $x));
    }

    public function dispatch($method, $url)
    {
        if (strpos($url, '/') == 0) {
            $args = explode("/", substr($url, 1));
        } else {
            $args = explode("/", $url);
        }

        $route = array_shift($args);
        $found = $this->search($method, $route);
        if ($found == false) {
            return false;
        }
        $target = array_shift(array_values($found));

        return $target->function->invokeArgs($args);
    }
}
