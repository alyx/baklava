<?php namespace Router;
include_once('radix.php');

class Route {
    public $arguments = 0;
    public $argList = array();
    public $function;

    public function __construct($name, $handler)
    {
        if (is_array($handler))
        {
            $this->function = call_user_func_array(\ReflectionMethod::__construct(), $handler);
        }
        else
        {
            $this->function = new \ReflectionFunction($handler);
        }
        //$argList = array();
        foreach ($this->function->getParameters() as $i => $paren)
        {
            $this->arguments++;
            $this->argList[] = $paren->getName();
        }
    }
}

class Router {
    private $routeList;

    function __construct() {
        $this->routeList = new \Trie();
        return $this;
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

    public function dispatch($method, $url) { 
        if (strpos($url, '/') == 0)
        {
            $args = explode("/", substr($url, 1));
        }
        else
        {
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
