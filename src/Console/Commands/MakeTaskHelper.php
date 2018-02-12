<?php

namespace LaraMaker\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MakeTaskHelper extends Command
{

    public $name = 'task-helper';

    public $files;

    public function __construct(Filesystem $files)
    {
        if (method_exists($this, '_configure')) {
            $this->_configure();
        }
        $this->files = $files;
        parent::__construct();
    }

    public function fire()
    {
        $this->makeModelTasks();
    }


    public function makeModelTasks()
    {
        $tables_in_db = DB::select('SHOW TABLES');
        $db = "Tables_in_".env('DB_DATABASE');
        foreach($tables_in_db as $table){
            $table = $table->{$db};
            $text = $this->greateTableTemplate($table);
            $path = 'TaskHelper' . DIRECTORY_SEPARATOR . $table .'.txt';
            $this->files->makeDirectory(dirname($path), 0777, true, true);
            $this->files->put($path, $text);
            echo 'aaa' . PHP_EOL;
        }
        $this->info('ass');
        return 'final';
    }

    protected function greateTableTemplate($table)
    {
        $singular  = str_singular($table);
        $singular = title_case($singular);
        $singular = str_replace('_', '', $singular);
        $ucSingular = ucfirst($singular);
        $path = 'admin/pages/' . str_replace('_', '-', $table) . '/';
        $task = 'Create ' . $singular. ' model.' . PHP_EOL . PHP_EOL
            .'In Admin path' . PHP_EOL
            . $ucSingular .'Validator,' . PHP_EOL
            . $ucSingular. 'Repository,' . PHP_EOL
            . $ucSingular . 'RepositoryInterface,' . PHP_EOL
            . 'bind repo it in RepositoryServiceProvider,'  . PHP_EOL
            . $ucSingular . 'Service,' . PHP_EOL
            . $ucSingular . 'Controller classes.' . PHP_EOL
            . 'Create views'  . PHP_EOL . PHP_EOL
            . $path .'index.blade' . PHP_EOL
            . $path . 'create.blade' . PHP_EOL
            . $path . 'show.blade' . PHP_EOL
            . $path . 'edit.blade' . PHP_EOL
            . $path . 'partial/form.blade'. PHP_EOL;

        $columns = Schema::getColumnListing($table);
        unset($columns[array_search('id', $columns)]);
        $columns = DB::select(sprintf('SHOW COLUMNS FROM %s;', $table));
        foreach ($columns as $columnData) {
            if ($columnData->Extra == 'auto_increment') {
                continue;
            }
            $task .= PHP_EOL . TAB . $columnData->Field . ' => required,';
            $task .= ' correspond ' . $columnData->Type . ',';
        }

        return $task;
    }
}