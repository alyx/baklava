<?php

namespace Baklava\Routes;

class Route
{
    public $arguments = 0;
    public $argList = array();
    public $isController;
    public $handle;

    public function __construct($name, $handler)
    {
        if (is_array($handler)) {
            $this->handle = call_user_func_array(\ReflectionMethod::__construct(), $handler);
        } 
        elseif(is_object($handler)) {
            $this->isController = true;
            $this->handle = $handler;
        } else {
            $this->handle = new \ReflectionFunction($handler);
        }

        if (!$this->isController)
        {
            foreach ($this->handle->getParameters() as $i => $paren) {
                $this->arguments++;
                $this->argList[] = $paren->getName();
            }
        }
    }

    public function run($args)
    {
        return $this->handle->invokeArgs($args);
    }
}
