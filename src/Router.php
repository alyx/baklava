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
        if (strpos($name, '/') == 0)
        {
            $name = substr($name, 1);
        }
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
        $split = parse_url($url);
        if (isset($split["path"]) && $split["path"] != "/")
        {
            $args = explode("/", substr($split["path"], 1));
            $route = array_shift($args);
        }
        else
        {
            $args = [];
            $route = "/";
        }

        $found = $this->search($method, $route);
        if ($found == false) {
            return false;
        }
        $target = array_shift(array_values($found));

        return $target->run($args);
    }
}
