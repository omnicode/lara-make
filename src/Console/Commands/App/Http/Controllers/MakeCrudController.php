<?php

namespace LaraMake\Console\Commands\App\Http\Controllers;

use LaraCrud\Controllers\LaraController;
use LaraMake\Console\Commands\Abstracts\ClassMaker;
use LaraMake\Console\Commands\Traits\FixPluralNameSuffixTrait;

class MakeCrudController extends ClassMaker
{
    use FixPluralNameSuffixTrait;

    /**
     * @var string
     */
    protected $name = 'crud-controller';

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
    protected $description = 'Make flexible CrudController';

    /**
     * @var bool
     */
    protected $makeBase = true;

    /**
     * @var string
     */
    protected $basePrefix = 'BaseCrud';

    /**
     * @var string
     */
    protected $parent = LaraController::class;

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

    /**
     *
     */
    protected function fixParent()
    {
        parent::fixParent();
        $service = $this->makeType('service');
        $this->methods = [
            'public' => [
                [
                    'name' => '__construct',
                    'arguments' => [
                        'service' => $service,
                    ],
                    'content' => 'parent::__construct();' . PHP_EOL
                        . TAB .TAB .'$this->baseService = $' .lcfirst(class_basename($service)) . ';' . PHP_EOL
                ]
            ]
        ];
    }


    protected function fixMethodArguments($arguments)
    {
        $service = $arguments['service'];
        $this->insertStubUse($service);

        $argumentStr = $this->objectArgumentTemplate($service);
        return $argumentStr;
    }

}