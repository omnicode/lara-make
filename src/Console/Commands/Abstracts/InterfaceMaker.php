<?php

namespace LaraMake\Console\Commands\Abstracts;

use LaraMake\Exceptions\LaraCommandException;

abstract class InterfaceMaker extends Maker
{
    /**
     * @var string
     */
    protected $type = 'interface';

    /**
     * @var string
     */
    protected $instance = 'Interface';

    /**
     * @var string
     */
    protected $suffix = 'Interface';

    /**
     * @var array
     */
    protected $parents = [];

    /**
     *
     */
    protected function fixParent()
    {
        parent::fixParent();
        $this->parent = $this->getBasePatternFullName();
        $this->methods = [];
    }

    /**
     * @param $pattern
     * @throws LaraCommandException
     */
    protected function fixStubContentFor($pattern)
    {
        if (!empty($this->parents[$this->parent])) {
            array_unshift($this->parents,$this->parent);
        }

        //TODO also check one inerface imlement two inteface and two interface imlements one interface

        if (in_array($pattern, $this->parents)) {
            if ($pattern != $this->getBasePatternFullName()) {
                //TODO improve
                $message = 'This input interface can not be implements self, fix it in class command';
                throw new LaraCommandException($message);
            }
        }

        //TODO check interface methods compatible example FailedJobProviderInterface::class, RepositoryInterface::class,
        $implements = [];
        foreach ($this->parents as $key => $parent) {
            if (!interface_exists($parent) && in_array($parent, config(self::ConfigMakesPath))) {
                $message = 'This parent interface does not exist fix it';
                throw new LaraCommandException($message);
            }
            if (array_search($parent, array_dot($implements))) {
                //TODO show message wich say one interface already implements other
                unset($this->parents[$key]);
            }
            $implements[$parent] = array_keys(class_implements($parent));
        }

        foreach ($this->parents as $parent) {
            $parent = $this->insertStubUse($parent);
            if (!str_contains($this->stubContent, ', _parent')) {
                $this->stubContent = str_replace('_parent','extends ' . $parent . ', _parent', $this->stubContent);
            } else {
                $this->stubContent = str_replace('_parent', $parent . ', _parent', $this->stubContent);
            }
        }

        $this->stubContent = str_replace(', _parent', '', $this->stubContent);
        parent::fixStubContentFor($pattern);
    }
}
