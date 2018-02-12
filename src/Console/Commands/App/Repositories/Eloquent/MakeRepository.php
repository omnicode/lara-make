<?php

namespace LaraMake\Console\Commands\App\Repositories\Eloquent;

use LaraMake\Console\Commands\Abstracts\ClassMaker;
use LaraRepo\Contracts\RepositoryInterface;
use LaraRepo\Eloquent\AbstractRepository;

class MakeRepository extends ClassMaker
{
    /**
     * @var string
     */
    protected $type = 'abstract class';

    /**
     * @var string
     */
    protected $name = 'repository';

    /**
     * @var string
     */
    protected $instance = 'Repository';

    /**
     * @var string
     */
    protected $suffix = 'Repository';

    /**
     * @var string
     */
    protected $rootPath = 'app' . DIRECTORY_SEPARATOR . 'Repositories' . DIRECTORY_SEPARATOR . 'Eloquent';

    /**
     * @var string
     */
    protected $rootNameSpace = 'App' . DIRECTORY_SEPARATOR . 'Repositories' . DIRECTORY_SEPARATOR . 'Eloquent';

    /**
     * @var string
     */
    protected $description = 'Make flexible Repository with extends LaraRepo\Eloquent\AbstractRepository';

    /**
     * @var bool
     */
    protected $makeBase = true;

    /**
     * @var string
     */
    protected $parent = AbstractRepository::class;

    /**
     * @var array
     */
    protected $interfaces = [
        RepositoryInterface::class
    ];

    /**
     *
     */
    protected function fixParent()
    {
        parent::fixParent();
        $this->type = 'class';

        $interface = $this->makeType('repository-interface');
        $this->interfaces = [
            $interface
        ];

//        $interfaceName = $instance->getCorrectedPatternName($this->clearSuffix($this->getNameInput()));
//        $interfaceFullName = $instance->getPatternFullName($interfaceName);
//
//        if (!interface_exists($interfaceFullName)) {
//            $this->call(self::LARA_MAKE . 'repository-interface', ['name' => $interfaceName]);
//            $composer = app(Composer::class);
//            $composer->dumpAutoloads();
//        }
//
//        //TODO fix

        $model = $this->makeType('model');

        $this->methods = [
            'public' => [
                [
                    'name' => 'modelClass',
                    'content' => $model
                ]
            ]
        ];
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function getMethodContentBy($data)
    {
        $class = $data['content'];
        $class = $this->insertStubUse($class);
        return "return $class::class;";
    }
}