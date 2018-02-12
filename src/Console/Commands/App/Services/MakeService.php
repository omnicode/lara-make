<?php

namespace LaraMaker\Console\Commands\App\Services;

use LaraMaker\Console\Commands\Abstracts\ClassMaker;
use LaraService\Services\LaraService;

class MakeService extends ClassMaker
{
    /**
     * @var string
     */
    protected $name = 'service';

    /**
     * @var string
     */
    protected $instance = 'Service';

    /**
     * @var string
     */
    protected $suffix = 'Service';

    /**
     * @var string
     */
    protected $rootPath = 'app' . DIRECTORY_SEPARATOR . 'Services';

    /**
     * @var string
     */
    protected $rootNameSpace = 'App' . DIRECTORY_SEPARATOR . 'Services';

    /**
     * @var string
     */
    protected $description = 'Make flexible Services with extends LaraService\Services\LaraService';

    /**
     * @var bool
     */
    protected $makeBase = true;

    /**
     * @var string
     */
    protected $parent = LaraService::class;

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
     *
     */
    protected function fixParent()
    {
        parent::fixParent();
        $repository = $this->makeType('repository');
        $validator = $this->makeType('validator');
        $this->methods = [
            'public' => [
                [
                    'name' => '__construct',
                    'arguments' => [
                        'validator' => $validator,
                        'repository' => $repository,
                    ],
                    'content' => '$this->baseRepository = $' .lcfirst(class_basename($repository)) . ';' . PHP_EOL
                        . TAB . TAB . '$this->baseValidator = $' . lcfirst(class_basename($validator)) . ';',
                ]
            ]
        ];
    }

    protected function fixMethodArguments($arguments)
    {
        $repository = $arguments['repository'];
        $repositoryInterface = str_replace('Eloquent', 'Contracts', $repository);
        $validator = $arguments['validator'];
        $this->insertStubUse(sprintf('%sInterface as %s', $repositoryInterface, class_basename($repository)));
        $this->insertStubUse($validator);

        $argumentStr = $this->objectArgumentTemplate($repository);
        $argumentStr .= ', ' . $this->objectArgumentTemplate($validator);
        return $argumentStr;
    }


}