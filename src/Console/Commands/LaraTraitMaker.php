<?php
namespace LaraMaker\Console\Commands;

use LaraMaker\Console\Commands\Abstracts\TraitMaker;
use LaraModel\Traits\FullNameTrait;
use LaraModel\Traits\ModelExtrasTrait;

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
