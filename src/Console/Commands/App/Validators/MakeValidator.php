<?php

namespace LaraMake\Console\Commands\App\Validators;

use LaraMake\Console\Commands\Abstracts\ClassMaker;
use LaraMake\Console\Commands\Traits\InsertMethodsDirectlyTrait;
use LaraValidation\LaraValidator;

class MakeValidator extends ClassMaker
{
    use  InsertMethodsDirectlyTrait;

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
    protected $methods = [
        'public' => [
            [
                'name' => 'validationDefault',
                'content' => 'return $this->validator;'
            ]
        ]
    ];
}