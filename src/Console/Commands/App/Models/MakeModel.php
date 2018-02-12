<?php

namespace LaraMaker\Console\Commands\App\Models;

use LaraMaker\Console\Commands\Abstracts\ClassMaker;
use LaraMaker\Console\Commands\Traits\InsertPropertiesDirectlyTrait;
use LaraModel\Models\LaraModel;

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
//
//    /**
//     *
//     */
//    protected function insertStubProperties()
//    {
//        if($this->currentPattern != $this->getBasePatternFullName()) {
//            $defaultName = str_replace($this->rootNameSpace. DIRECTORY_SEPARATOR, '', $this->currentPattern);
//            $table = $this->_tables[$defaultName];
//            $columns = Schema::getColumnListing($table);
//            unset($columns[array_search('id', $columns)]);
//            $this->properties = [
//                'public' => [
//                    '$fillable' => $columns
//                ]
//            ];
//            parent::insertStubProperties();
//        }
//    }
//
//    protected function makeBasedDb()
//    {
//        $tables = LaraDb::getTables();
//        $this->processTable($tables);
//
//        $this->fixParent();
//        $this->fillPatterns($tables);
//
//        $this->makeDbCorrespondType('Validator');
//        $this->makeDbCorrespondType('RepositoryInterface', 'repository-interface');
//        $this->makeDbCorrespondType('Repository');
//        $this->makeDbCorrespondType('Service');
//        $this->makeDbCorrespondType('Controllers', 'crudController');
//    }
//
//    protected function makeDbCorrespondType($type, $command = '') {
//        if (empty($command)) {
//            $command = lcfirst($type);
//        }
//        foreach (array_keys($this->_tables) as $index => $model) {
//            $this->call('lara-make:'. $command, ['name' => $model . $type ]);
//        }
//    }
//
//    protected function processTable(&$tables)
//    {
//        foreach ($tables as $index => $table) {
//            $_table= str_singular(title_case($table));
//            $_table = str_replace('_', '', $_table);
//            $tables[$index] = $_table;
//            $this->_tables[$_table] = $table;
//        }
//    }

}