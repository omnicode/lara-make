<?php

namespace LaraMake\Console\Commands\Abstracts;

use Illuminate\Support\Facades\App;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\Command;
use Illuminate\Support\Composer;
use LaraMake\Console\Commands\Parser;
use LaraMake\Console\Commands\Traits\DynamicMethodExampleTrait;
use LaraMake\Console\Commands\Traits\MakeSignatureTrait;
use LaraMake\Console\Commands\Traits\ProcessInputTrait;
use LaraMake\Console\Commands\Traits\Replace\ReplaceTrait;
use LaraMake\Exceptions\LaraCommandException;
use LaraSupport\Str;

abstract class BaseMaker extends Command
{
    use ProcessInputTrait, MakeSignatureTrait, ReplaceTrait, DynamicMethodExampleTrait;

    /**
     * All commands automatically must be starts with this
     */
//    const LARA_MAKE = 'lara-make:';
    const LARA_MAKE = 'l:';

//    /**
//     * All paths which makes by lara-make it must be set in config path using this
//     */
//    const CONFIG_MAKES_PATH = 'lara_maker.makes';

    /**
     * The stub name.
     * It it must be inside of stubs
     * and stubs must be sibling Commands
     *
     * @var string
     */
    public $stub;

    /**
     * @var
     */
    protected $_pattern;

    /**
     * @var
     */
    protected $__pattern;


    /**
     * Current file full path
     *
     * @var
     */
    public $path;

    /**
     * user defined relative path
     *
     * @var
     */
    protected $__path;

    /**
     * The relative path project where must be insert generated files
     *
     * @var string
     */
    public $rootPath;

    /**
     * define root path, if not input root path must be class root path
     * @var
     */
    protected $__rootPath;

    /**
     * @TODO
     * @var bool
     */
    public $dumpAutoload = false;

    /**
     * info parameter
     *
     * Define pattern instance which must be show for success generation pattern,
     * And add in parent name
     * examples-pattern:Class, Trait, Interface
     *
     * @var
     */
    public $instance;

    /**
     * @TODO tmp must be make dynamically and validate
     *
     * @var
     */
    public $extension;

    //    /**
//     * @var string
//     */
//    protected $_config = '_config';

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @var Parser
     */
    protected $parser;

    /**
     * Also for dynamically replace keywords define "replaceExampleKey($content, $keyword)"
     * modify keywords and return result {not null}
     *
     * for example if $keywords = ['_name'] make "replaceNameKey($content, $keyword)"
     * for example if $keywords = ['_name'] make "trimNameKey($content, $keyword)"
     * where as argument pass stubContent  and keyword must be _name
     *
     * make trimFinalContent($content) to finally trim result with not needed result
     *
     *
     * PhpMaker constructor.
     * @param Filesystem $files
     * @throws LaraCommandException
     */
    public function __construct(Filesystem $files, Parser $parser)
    {
        $this->setSignature();
        $this->files = $files;
        $this->parser = $parser;
        parent::__construct();
    }

    /**
     * User input class1,class2,app\\class1,app/class1, --force --namespace SomeNamespace
     *
     *
     * {rootNameSpace} . {?namespace} . {patternNamespace} . pattern
     *
     * @return bool
     * @throws LaraCommandException
     */
    public function handle()
    {
        $stubContent = $this->getStubContent();
        $stubContent = $this->validateStubContent($stubContent);
        // firstly set options then arguments
//        if (starts_with($pattern, $this->_config)) {
////            // @TODO make based config.file
////            if(method_exists($this, 'makeBasedConfig')) {
////                return $this->makeBasedConfig();
////            }
////            $message = sprintf('%s must be contain makeBasedConfig method', get_class($this));
////            throw new LaraCommandException($message);
//        }

        $this->setOptions();

        $this->setArguments();
        $pattern = $this->argument('pattern');

        if (is_string($pattern) && starts_with($pattern, config('lara_make.by_database'))) {
            if(method_exists($this, 'makeBasedDb')) {
                return $this->makeBasedDb($pattern, $stubContent);
            }
            $message = sprintf('%s must be contain makeBasedDb method', get_class($this));
            throw new LaraCommandException($message);
        }

        $patterns = (array) $pattern;
        return $this->fillPatterns($patterns, $stubContent);
    }

    /**
     *
     * @return string
     * @throws LaraCommandException
     */
    public function getStubContent($path = __DIR__)
    {
        if (empty($this->stub)) {
            $message = $this->attentionSprintF('%s must be filled when used default getStubContent method, or you must be change method content');
            throw new LaraCommandException($message);
        }

        return $this->getStubContentByStubPath($this->stub, $path);
    }

    /**
     * @param $stub
     * @param string $path
     * @return string
     */
    public function getStubContentByStubPath($stub, $path = __DIR__)
    {
        $path = $this->getCommandsPath($path);
        $path .= 'stubs' . DIRECTORY_SEPARATOR . $stub;
        $stub = $this->files->get($path);
        return $stub;
    }

    /**
     * @param $content
     * @return mixed
     */
    public function fixNewLinesOfStub($content)
    {
        $content = str_replace(
            [PHP_EOL, "\r\n", "\n", '\r\n', '\n'],
            ['_tmp_', '_tmp_', '_tmp_', '_tmp_', '_tmp_'],
            $content
        );
        $content = str_replace('_tmp_', PHP_EOL, $content);
        return $content;
    }

    /**
     * @param $content
     * @return mixed
     * @throws LaraCommandException
     */
    public function validateStubContent($content)
    {
        // @TODO make preg match for all structure
        // preg_match_all("/\{\{(.+)\}\}/", $stubContent, $matches);
        $content = $this->fixNewLinesOfStub($content);
        $keyEnds = Str::after($this->keyWordStructure, '%s');
        $keyStarts = Str::before($this->keyWordStructure, '%s');
        $keys = [];

        foreach (explode($keyStarts, $content) as $key) {
            if (str_contains($content, $keyEnds)) {
                if (false != Str::before($key, $keyEnds)) {
                    $keys[] = Str::before($key, $keyEnds);
                }
            }
        }

//        $pregPattern = str_replace('%s', '(.*?)', $this->keyStructure);
//        $pregPattern  = sprintf('/%s/s', $pregPattern);
//        preg_match_all($pregPattern, $content, $keys);
//        $keys = $keys[1];

        $keys = array_unique($keys);
        $keyWords = array_keys($this->keyWords);
        $diffKey = array_diff($keys, $keyWords);

        if ($diffKey) {
            // @TODO improve message with incorrect keyword
            $message = 'check your keywords ' . implode(',', $diffKey);
            throw new LaraCommandException($message);
        }

        $diffKey = array_diff($keyWords, $keys);
        if ($diffKey) {
            // @TODO improve message with incorrect keyword
            $message = 'check your keywords ' . implode(',', $diffKey);
            throw new LaraCommandException($message);
        }

        return $content;
    }

    /**
     * Set all user command input options and validate it
     *
     * @throws LaraCommandException
     */
    protected function setOptions()
    {
        foreach ($this->propertyWithOptions as $property => $option) {
            $option = $this->option($option);
            $this->{'__' . $property} = $option;
        }
    }

    /**
     * Set all user command input options and validate it
     *
     * @throws LaraCommandException
     */
    protected function setArguments()
    {
        foreach ($this->propertyWithArguments as $property => $argument) {
            $argument = $this->argument($argument);
            $argument = $this->processInput($property, $argument);
            $this->{'_' . $property} = $argument;
        }
    }

    /**
     * Correct all user input pattern name, namespace, all options and make correspond files
     *
     * @param $patterns
     * @param $stubContent
     * @return bool
     */
    public function fillPatterns($patterns, $stubContent)
    {
        foreach ($patterns as $pattern) {
            if(false == $this->createFileBy($pattern, $stubContent)) {
                // @TODO show not saved infon
                return false;
            }
        }

        return true;
    }

    /**
     * @param $pattern
     * @param null $content
     * @return int|null
     */
    public function createFileBy($pattern, $content)
    {
        $path = $this->getPath($pattern, $this->extension);
        $this->path = $path;
        if ($this->files->exists($path)) {
            if (!$this->__confirmOverwrite) {
                $message = $this->attentionSprintF('This %s class already exists do you want to override it', $pattern);
                if (!$this->confirm($message)) {
                    return null;
                }
            }
        } else {
            $this->makeDirectory($path);
            if ($this->dumpAutoload) {
                // @TODO
                $this->composerDumpAutoload();
            }
        }

        $this->__pattern = $pattern;
        $content = $this->replaceStubContent($content);
        $message = $this->attentionSprintF('In path  %s created successfully', $path . ' ' . $pattern . ' ' . $this->instance);
        $this->info($message);
        if (trim($content)) {
            return $this->files->put($path, $content);
        }

        $this->files->put($path, $content);
        return true;
    }

    /**
     * Get generation file full path
     *
     * @param $name
     * @param $extension
     * @return string
     */
    protected function getPath($name, $extension)
    {
        $pathStarts = $this->getRelativePath();
        $path = $pathStarts . $name;
        if (strpos($path, DIRECTORY_SEPARATOR)) {
            $path = lcfirst($path);
        }

        return base_path($path) . $extension;
    }

    /**
     * @param $path
     */
    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
    }

    /**
     *
     */
    protected function composerDumpAutoload()
    {

        $composer = App::make(Composer::class);
        $composer->dumpAutoloads();
    }

    /**
     * @return string
     */
    public function getRelativePath()
    {
        $rotPath = $this->__rootPath ?? $this->rootPath;
        $path = $rotPath ? $rotPath . DIRECTORY_SEPARATOR : '';
        $path .= $this->__path ? $this->__path . DIRECTORY_SEPARATOR : '';
        return $path;
    }

    /**
     * @param string $dir
     * @return bool|string
     */
    public function getCommandsPath($dir = __DIR__)
    {
        return Str::before($dir, 'Commands', 1);
    }

    /**
     * @param null $key
     * @return array|string
     */
    public function argument($key = null)
    {
        $argument =  parent::argument($key);
        $argument = $this->parser->parseInput($key, $argument);
        return $this->processInput($key, $argument);
    }

    /**
     * @param null $key
     * @return array|string
     */
    public function option($key = null)
    {
        $option =  parent::option($key);
        $option = $this->parser->parseInput($key, $option);
        return $this->processInput($key, $option);
    }
}
