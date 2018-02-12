<?php
namespace LaraMaker\Console\Commands;

use LaraMaker\Console\Commands\Abstracts\InterfaceMaker;

class LaraInterfaceMaker extends InterfaceMaker
{
    /**
     * @var string
     */
    protected $name = 'interface';

    /**
     * @var bool
     */
    protected $makeBase = true;

    /**
     * @var string
     */
    protected $rootPath = 'app' . DIRECTORY_SEPARATOR . 'Interfaces';

    /**
     * @var string
     */
    protected $rootNameSpace = 'App' . DIRECTORY_SEPARATOR . 'Interfaces';
    
}
