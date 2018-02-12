<?php
namespace LaraMake\Console\Commands;

use LaraMake\Console\Commands\Abstracts\TraitMaker;

class LaraTraitMaker extends TraitMaker
{
    /**
     * @var string
     */
    protected $name = 'trait';

    /**
     * @var bool
     */
    protected $makeBase = true;

    /**
     * @var string
     */
    protected $rootPath = 'app' . DIRECTORY_SEPARATOR . 'Traits';

    /**
     * @var string
     */
    protected $rootNameSpace = 'App' . DIRECTORY_SEPARATOR . 'Traits';

}
