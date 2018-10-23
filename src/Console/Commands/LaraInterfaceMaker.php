<?php
namespace LaraMake\Console\Commands;

use LaraMake\Console\Commands\Abstracts\InterfaceMaker;
use LaraRepo\Contracts\RepositoryInterface;

class LaraInterfaceMaker extends InterfaceMaker
{
    /**
     * @var string
     */
    public  $commandName = 'interface';

    public $makeBase = true;

    public $parents = [RepositoryInterface::class];

}
