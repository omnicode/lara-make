<?php

namespace LaraMake\Console\Commands\Abstracts;

use LaraMake\Console\Commands\Traits\InsertPropertyTrait;
use LaraMake\Console\Commands\Traits\InsertTrait_Trait;

abstract class TraitMaker extends Maker
{
    use InsertTrait_Trait, InsertPropertyTrait;

    /**
     * @var string
     */
    protected $type = 'trait';

    /**
     * @var string
     */
    protected $instance = 'Trait';

    /**
     * @var string
     */
    protected $suffix = 'Trait';

    /**
     * @var array
     */
    protected $parents = [];

    /**
     * @param $pattern
     */
    protected function fixStubContentFor($pattern)
    {
        if (!empty($this->parent)) {
            array_unshift($this->parents,$this->parent);
        }

        if (!empty($this->parents)) {
            $this->insertStubTraits();
            $this->stubContent = str_replace(', _trait', ';', $this->stubContent);
        }
        parent::fixStubContentFor($pattern);
    }
}
