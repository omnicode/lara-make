<?php
namespace LaraMaker\Console\Commands;

use LaraMaker\Console\Commands\Abstracts\ClassMaker;

class LaraClassMaker extends ClassMaker
{
//    protected $name = 'class';

    /**
     * @var string
     */
    protected $name = 'validator';

    /**
     * @var string
     */
    protected $instance = 'Validator';

    /**
     * @var string
     */
    protected $suffix = 'Validator';

    /**
     * @var string
     */
    protected $rootPath = 'app' . DIRECTORY_SEPARATOR . 'Validators';

    /**
     * @var string
     */
    protected $rootNameSpace = 'App' . DIRECTORY_SEPARATOR . 'Validators';

    /**
     * @var string
     */
    protected $description = 'Make flexible Validator with extends LaraValidation\LaraValidator';

    /**
     * @var bool
     */
    protected $makeBase = true;

    /**
     * @var string
     */
    protected $parent = LaraValidator::class;

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

    ];

}
