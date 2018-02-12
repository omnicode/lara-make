<?php

namespace LaraMake\Console\Commands\Traits;

use LaraMake\Exceptions\LaraCommandException;

trait InsertTrait_Trait
{
    /**
     * @var array
     */
    protected $traits = [];

    /**
     *
     */
    protected function insertStubTraits()
    {
        if (!empty($this->traits)) {
            $this->insertTraits($this->traits);
            $this->stubContent = str_replace(', _trait' . PHP_EOL, ';' . PHP_EOL . TAB, $this->stubContent);
        } else {
            $this->stubContent = str_replace(TAB . '_trait' . PHP_EOL . PHP_EOL, '', $this->stubContent);
        }
    }

    /**
     * @param $traits
     * @throws LaraCommandException
     */
    protected function insertTraits($traits)
    {
        foreach ($traits as $trait) {

            if (!trait_exists($trait)) {
                $message = sprintf("%s trait does not exist. Fix it in '%s' class", $trait, get_class($this));
                throw new LaraCommandException($message);
            }
            //TODO fix when 2 traits have some method

            $trait = $this->insertStubUse($trait);
            if (str_contains($this->stubContent, ', _trait')) {
                $this->stubContent = str_replace('_trait',  $trait . ', _trait', $this->stubContent);
            } else {
                $this->stubContent = str_replace('_trait', 'use ' . $trait . ', _trait', $this->stubContent);
            }

        }
    }

}