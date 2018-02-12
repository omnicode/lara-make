<?php

namespace LaraMake\Console\Commands\App\Listeners;

use LaraMake\Console\Commands\Abstracts\ClassMaker;
use LaraMake\Console\Commands\Traits\InsertMethodsDirectlyTrait;

class MakeListener extends ClassMaker
{
    use InsertMethodsDirectlyTrait;

    /**
     * @var string
     */
    protected $name = 'listener';

    /**
     * @var string
     */
    protected $instance = 'Listener';


    /**
     * @var string
     */
    protected $rootPath = 'app' . DIRECTORY_SEPARATOR . 'Listeners';

    /**
     * @var string
     */
    protected $rootNameSpace = 'App' . DIRECTORY_SEPARATOR . 'Listeners';

    /**
     * @var string
     */
    protected $description = 'Make flexible Listeners';

    /**
     * @var bool
     */
    protected $makeBase = true;

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

    ];

    /**
     * @var array
     */
    protected $methods = [
        'public' => [
            [
                'name' => '__constract',
            ],
            [
                'name' => 'handle',
            ]
        ]
    ];

}