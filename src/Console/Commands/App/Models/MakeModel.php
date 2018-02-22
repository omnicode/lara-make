<?php

namespace LaraMake\Console\Commands\App\Models;

use LaraMake\Console\Commands\Abstracts\ClassMaker;
use LaraMake\Console\Commands\Traits\InsertPropertiesDirectlyTrait;
use LaraModel\Models\LaraModel;
use LaraSupport\LaraDB;

class MakeModel extends ClassMaker
{
    use InsertPropertiesDirectlyTrait;

    /**
     * @var string
     */
    protected $name = 'model';

    /**
     * @var string
     */
    protected $instance = 'Model';

    /**
     * @var string
     */
    protected $rootPath = 'app' . DIRECTORY_SEPARATOR . 'Models';

    /**
     * @var string
     */
    protected $rootNameSpace = 'App' . DIRECTORY_SEPARATOR . 'Models';

    /**
     * @var string
     */
    protected $description = 'Make flexible Models with extends LaraModel\Models\LaraModel';

    /**
     * @var bool
     */
    protected $makeBase = true;

    /**
     * @var string
     */
    protected $parent = LaraModel::class;

    /**
     * @var
     */
    private $_tables;

    /**
     * @var array
     */
    protected $properties  = [
        'public' => [
            '$fillable' => [],
            '$indexable' => [],
            '$showable' => [],
            '$listable' => [],
            '$_relations' => [],
        ]
    ];

    /**
     *
     */
    protected function insertStubProperties()
    {
        if($this->currentPattern != $this->getBasePatternFullName()) {
            $defaultName = str_replace($this->rootNameSpace. DIRECTORY_SEPARATOR, '', $this->currentPattern);
            $table = $this->_tables[$defaultName];
            $columns = LaraDB::getColumnsFullInfo($table);
            unset($columns['id']);
            $this->properties = [
                'public' => [
                    '$fillable' => array_keys($columns)
                ]
            ];
            parent::insertStubProperties();
        }
    }

    protected function makeBasedDb()
    {
        $dbStructure = LaraDB::getDBStructure();
        $tables = starts_with($this->getNameInput(), '_db:')
            ? $this->getPatternsByPrefix('_db:')
            : $tables = array_keys($dbStructure);

        $this->processTable($tables);

        $this->fixParent();
        $this->fillPatterns($tables);

        $this->makeDBCorrespondType('Validator');
        $this->makeDBCorrespondType('RepositoryInterface', 'repository-interface');
        $this->makeDBCorrespondType('Repository');
        $this->makeDBCorrespondType('Service');
        $this->makeDBCorrespondType('Controller', 'crud-controller');
    }

    /**
     * @param $type
     * @param string $command
     */
    protected function makeDBCorrespondType($type, $command = '') {
        if (empty($command)) {
            $command = lcfirst($type);
        }
        foreach (array_keys($this->_tables) as $index => $model) {
            $this->call('lara-make:'. $command, ['name' => $model . $type ]);
        }
    }

    /**
     * @param $tables
     */
    protected function processTable(&$tables)
    {
        foreach ($tables as $index => $table) {
            $_table= str_singular(title_case($table));
            $_table = str_replace('_', '', $_table);
            $tables[$index] = $_table;
            $this->_tables[$_table] = $table;
        }
    }

}