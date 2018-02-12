<?php

namespace LaraMake\Console\Commands\App\Console\Commands;

use LaraMake\Console\Commands\Abstracts\ClassMaker;

class MakeClassCommand extends ClassMaker
{
    /**
     * @var string
     */
    protected $name = 'class-command';

    /**
     * @var string
     */
    protected $instance = 'Class Command';

    /**
     * @var string
     */
    protected $rootPath = 'app' . DIRECTORY_SEPARATOR . 'Console'. DIRECTORY_SEPARATOR . 'Commands';

    /**
     * @var string
     */
    protected $rootNameSpace = 'App' . DIRECTORY_SEPARATOR . 'Console'. DIRECTORY_SEPARATOR . 'Commands';

    /**
     * @var string
     */
    protected $description = 'Make flexible Class Commands with extends LaraMake\Console\Commands\Abstracts\ClassMaker';

    /**
     * @var string
     */
    protected $parent = ClassMaker::class;

    /**
     * @var array
     */
    protected $interfaces = [

    ];

    /**
     * @var array
     */
    protected $traits = [

    ];

    /**
     * @var array
     */
    protected $properties = [
        'protected' => [
            '$name'     => 'command_name',
            '$instance' => 'Model',
            '$suffix'   => '',
            '$rootPath' => 'app',
            '$rootNameSpace' => 'App',
            '$description' => 'Some DEscription',
            '$makeBase' => true,
            '$parent' => false,
            '$interfaces' => [],
            '$traits' => [],
            '$properties' => [],
            '$methods' => [],
        ]
    ];

}