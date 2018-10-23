<?php
namespace LaraMake\Console\Commands;

use LaraMake\Console\Commands\Abstracts\TTraitMaker;

class LaraTraitMaker extends TTraitMaker
{
    /**
     * @var string
     */
    public  $commandName = 'trait';

    /**
    /**
     * @var string
     */
    public  $rootPath = 'app' . DIRECTORY_SEPARATOR . 'Traits';

    /**
     * @var string
     */
    public  $rootNameSpace = 'App' . DIRECTORY_SEPARATOR . 'Traits';

}
