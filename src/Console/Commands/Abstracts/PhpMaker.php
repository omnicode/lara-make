<?php

namespace LaraMake\Console\Commands\Abstracts;

abstract class PhpMaker extends BaseMaker
{

    public $ignoreTables = [
        'migrations',
        'password_resets',
    ];

    public $ignoreColumns = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $ignoreTableColumns;

    public $stabTables;



    /**
     * @var array
     */
    public $keyWords = [
        'namespace',
        'use',
    ];

    /**
     * @var
     */
    protected $__use;

    /**
     * @var
     */
    protected $__namespace;

    /**
     * @var array
     */
    public $notNeedOptions = [
        'namespace'
    ];

    /**
     * @var string
     */
    public $stub = 'general' . DIRECTORY_SEPARATOR . 'php.stub';

    /**
     * @var string
     */
    public $extension = '.php';


    public $namespaceShorts;

    /**
     * @param $value
     * @return array
     */
    public function processUseInput($value)
    {
        $value = (array) $value;
        $namespaces = [];
        foreach ($value as $namespace) {
            $this->addNamespaceIn($namespace, $namespaces);
        }

        return $namespaces;
    }

    public function getNamespaceBaseName($namespace, &$namespaces)
    {
        // it is no good code must be improve
        if (empty($this->namespaceShorts[$namespace])) {
            $this->addNamespaceIn($namespace, $namespaces);
        }

        return $this->namespaceShorts[$namespace];
    }


    /**
     * @param $namespace
     * @param $namespaces
     */
    public function addNamespaceIn($namespace, &$namespaces)
    {
        $namespaces = (array) $namespaces;
        $baseName = class_basename($namespace);
        if (empty($namespaces[$baseName])) {
            $namespaces[$baseName] = $namespace;
            $this->namespaceShorts[$namespace] = $baseName;
        } else {
            if (starts_with($namespace, $this->__namespace)) {
                $_namespace = str_replace_first($this->__namespace . DIRECTORY_SEPARATOR, '', $namespace);
                if( false == strpos($_namespace, DIRECTORY_SEPARATOR)) {
                    $oldNamespace = $namespaces[$baseName];
                    $namespaces[$baseName] = $namespace;
                    $this->namespaceShorts[$namespace] = $baseName;
                    $this->addFirstLevelNamespaceIn($oldNamespace, $namespaces);
                }
            } else {
                // @TODO improve with many levels match. this time only one level
                $this->addFirstLevelNamespaceIn($namespace, $namespaces);
            }
        }
    }

    public function addFirstLevelNamespaceIn($namespace, &$namespaces)
    {
        $_namespace = str_replace_last(DIRECTORY_SEPARATOR, '', $namespace);
        $baseName = class_basename($_namespace);
        $namespaces[$baseName] = $namespace . ' as ' . $baseName;
        $this->namespaceShorts[$namespace] = $baseName;
    }

    /**
     * @return string
     */
    public function getNamespaceKeyWordPropertyValue()
    {
        $path = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $this->path);
        $path = str_replace($this->extension, '', $path);
        $path = explode(DIRECTORY_SEPARATOR, $path);
        array_pop($path);
        $path = implode(DIRECTORY_SEPARATOR, $path);

        $composer = json_decode(file_get_contents(base_path('composer.json')), true);
        $prs4 = (array) data_get($composer, 'autoload.psr-4');

        foreach ($prs4 as $namespace => $namespacePath) {
            $namespacePath = str_replace("/", DIRECTORY_SEPARATOR, $namespacePath);
            if (starts_with($path, $namespacePath)) {
                return str_replace_first($namespacePath, $namespace, $path);
            }
        }

        $classMap = (array) data_get($composer, 'autoload.classmap');
        // @TODO
        return ucfirst($path);
    }

    /**
     * @param $content
     * @param $keyWord
     * @param $input
     * @return mixed
     */
    public function replaceNamespaceKeyWord($content, $keyWord, $input)
    {
        if (empty($input)) {
            return str_replace($keyWord . PHP_EOL  . PHP_EOL, '', $content);
        }

        $change = sprintf('namespace %s;', $input);
        return str_replace($keyWord, $change, $content);
    }

// @TODO
//    /**
//     * @param $keyWord
//     * @return string
//     */
//    public function getUseKeyWordTemplate($keyWord)
//    {
//        return 'use %s;' . PHP_EOL . $keyWord;
//    }

    /**
     * @param $content
     * @param $keyWord
     * @return mixed
     */
    public function trimUseKeyWord($content, $keyWord)
    {


        $this->__use = (array) $this->__use;
        $this->__use = array_unique($this->__use);
        $template = 'use %s;' . PHP_EOL;

        $to = '';
        sort($this->__use);
        foreach ($this->__use as $namespace) {
            if (starts_with($namespace, $this->__namespace)) {
                $_namespace = str_replace_first($this->__namespace . DIRECTORY_SEPARATOR, '', $namespace);
                if( false == strpos($_namespace, DIRECTORY_SEPARATOR)) {
                    // namespace and file namespace is same
                    continue;
                }
            }

            $to .= sprintf($template, $namespace);
        }

        if (empty($to)) {
            return str_replace(PHP_EOL . $keyWord . PHP_EOL, '', $content);
        }

        return str_replace($keyWord . PHP_EOL, $to, $content);
    }
}
