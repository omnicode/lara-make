<?php

namespace LaraMake\Console\Commands\Traits;

trait InsertMethodsDirectlyTrait
{
    /**
     *
     */
    protected function fixParent()
    {
        $_methods = $this->methods;
        $this->methods = [];
        parent::fixParent();
        $this->methods = $_methods;
    }
}
