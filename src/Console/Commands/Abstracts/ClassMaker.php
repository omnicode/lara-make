<?php

namespace LaraMake\Console\Commands\Abstracts;

use LaraMake\Console\Commands\Traits\InsertInterfaceTrait;
use LaraMake\Console\Commands\Traits\InsertPropertyTrait;
use LaraMake\Console\Commands\Traits\InsertTrait_Trait;

abstract class ClassMaker extends Maker
{
    use InsertPropertyTrait, InsertTrait_Trait, InsertInterfaceTrait;

    /**
     * @var string
     */
    protected $type = 'class';

    /**
     * @var
     */
    protected $parent;

    /**
     * @param $pattern
     */
    protected function fixStubContentFor($pattern)
    {
        $this->insertStubInterfaces();
        $this->insertStubTraits();
        parent::fixStubContentFor($pattern);
    }
}
