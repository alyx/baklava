<?php

namespace Baklava;

class BaseController
{
    function invokeArgs($args)
    {
        $target = (array_shift($args) ?: "index");
        if (method_exists($this, $target))
        {
            return call_user_method_array($target, $this, $args);
        }
    }
}
