<?php

namespace LaraMake\Console\Commands\App\Http\ViewComposers;

use LaraMake\Console\Commands\Abstracts\ClassMaker;

class MakeViewComposer extends ClassMaker
{
    /**
     * @var string
     */
    protected $name = 'view-composer';

    /**
     * @var string
     */
    protected $instance = 'ViewComposer';

    /**
     * @var string
     */
    protected $suffix = 'ViewComposer';

    /**
     * @var string
     */
    protected $rootPath = 'app' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'ViewComposers';

    /**
     * @var string
     */
    protected $rootNameSpace = 'App' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'ViewComposers';

    /**
     * @var string
     */
    protected $description = 'Make flexible ViewComposer';

    /**
     * @var bool
     */
    protected $makeBase = false;

    /**
     * @var string
     */
    protected $parent = false;

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