<?php

namespace LaraMaker\Console\Commands\App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use LaraMaker\Console\Commands\Abstracts\ClassMaker;
use LaraMaker\Console\Commands\Traits\FixPluralNameSuffixTrait;

class MakeController extends ClassMaker
{
    use FixPluralNameSuffixTrait;

    /**
     * @var string
     */
    protected $name = 'controller';

    /**
     * @var string
     */
    protected $instance = 'Controller';

    /**
     * @var string
     */
    protected $suffix = 'Controller';

    /**
     * @var string
     */
    protected $rootPath = 'app' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Controllers';

    /**
     * @var string
     */
    protected $rootNameSpace = 'App' . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Controllers';

    /**
     * @var string
     */
    protected $description = 'Make flexible Controller';

    /**
     * @var bool
     */
    protected $makeBase = true;

    /**
     * @var string
     */
    protected $parent = Controller::class;

    /**
     * @var array
     */
    protected $interfaces = [

    ];

    /**
     * @var array
     */
    protected $traits = [
        AuthorizesRequests::class,
        DispatchesJobs::class,
        ValidatesRequests::class
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

    ];
}