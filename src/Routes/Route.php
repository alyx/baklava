<?php

namespace Baklava\Routes;

class Route
{
    public $arguments = 0;
    public $argList = array();
    public $function;

    public function __construct($name, $handler)
    {
        if (is_array($handler)) {
            $this->function = call_user_func_array(\ReflectionMethod::__construct(), $handler);
        } else {
            $this->function = new \ReflectionFunction($handler);
        }

        foreach ($this->function->getParameters() as $i => $paren) {
            $this->arguments++;
            $this->argList[] = $paren->getName();
        }
    }
}