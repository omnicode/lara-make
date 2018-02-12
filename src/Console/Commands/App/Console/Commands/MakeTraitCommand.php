<?php

namespace LaraMake\Console\Commands\App\Console\Commands;

use LaraMake\Console\Commands\Abstracts\TraitMaker;

class MakeTraitCommand extends TraitMaker
{
    /**
     * @var string
     */
    protected $type = 'class';

    /**
     * @var string
     */
    protected $name = 'trait-command';

    /**
     * @var string
     */
    protected $instance = 'Trait Command';

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
    protected $description = 'Make flexible Class Commands with extends LaraMake\Console\Commands\Abstracts\TraitMaker';

    /**
     * @var string
     */
    protected $parent = TraitMaker::class;

    /**
     * @var string
     */
    protected $suffix = '';

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
            '$parents' => [],
            '$properties' => [],
            '$methods' => [],
        ]
    ];

}