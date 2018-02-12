<?php

namespace LaraMake\Console\Commands\App\Repositories\Contracts;

use LaraMake\Console\Commands\Abstracts\InterfaceMaker;
use LaraRepo\Contracts\RepositoryInterface;

class MakeRepositoryInterface extends InterfaceMaker
{
    /**
     * @var string
     */
    protected $name = 'repository-interface';

    /**
     * @var string
     */
    protected $instance = 'RepositoryInterface';

    /**
     * @var string
     */
    protected $suffix = 'RepositoryInterface';

    /**
     * @var bool
     */
    protected $makeBase = true;

    /**
     * @var string
     */
    protected $rootPath = 'app' . DIRECTORY_SEPARATOR . 'Repositories' . DIRECTORY_SEPARATOR . 'Contracts';

    /**
     * @var string
     */
    protected $rootNameSpace = 'App' . DIRECTORY_SEPARATOR . 'Repositories' . DIRECTORY_SEPARATOR . 'Contracts';
    /**
     * @var string
     */
    protected $parent = RepositoryInterface::class;
    /**
     * @var string
     */
    protected $description = 'Make flexible Repository Interface with extends Repository Interface';
}