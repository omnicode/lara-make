<?php

namespace LaraMaker\Console\Commands\Abstracts;

use Illuminate\Support\Composer;
use LaraMaker\Console\Commands\Traits\InsertInterfaceTrait;
use LaraMaker\Console\Commands\Traits\InsertPropertyTrait;
use LaraMaker\Console\Commands\Traits\InsertTrait_Trait;
use LaraMaker\Exceptions\LaraCommandException;

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
