<?php

namespace LaraMaker\Console\Commands\Traits;


trait InsertPropertiesDirectlyTrait
{
    /**
     *
     */
    protected function fixParent()
    {
        $properties = $this->properties;
        $this->properties = [];
        parent::fixParent();
        $this->properties = $properties;
    }
}
