<?php

namespace LaraMaker\Console\Commands\App\Http\Middlewares;

use Illuminate\Filesystem\Filesystem;
use LaraMaker\Console\Commands\Abstracts\ClassMaker;
use LaraMaker\Console\Commands\App\LaraAppClassMaker;

class MakeMiddleware extends ClassMaker
{
    /**
     * @var string
     */
    protected $name = 'middleware';

    /**
     * @var string
     */
    protected $instance = 'Middleware';

    /**
     * @var string
     */
    protected $suffix = 'Middleware';

    /**
     * @var string
     */
    protected $rootPath = 'app' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Middleware';

    /**
     * @var string
     */
    protected $rootNameSpace = 'App' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Middleware';

    /**
     * @var string
     */
    protected $description = 'Make flexible Middleware';

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