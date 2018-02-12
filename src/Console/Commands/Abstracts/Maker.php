<?php

namespace LaraMake\Console\Commands\Abstracts;

use Illuminate\Support\Facades\Config;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\Command;
use Illuminate\Support\Composer;
use LaraMake\Console\Commands\Traits\CommentMakerTrait;
use LaraMake\Console\Commands\Traits\InsertMethodTrait;
use LaraMake\Console\Commands\Traits\InsertUseTrait;
use LaraMake\Console\Commands\Traits\TemplatesTrait;
use LaraMake\Exceptions\LaraCommandException;
use Symfony\Component\Console\Input\InputArgument;
use LaraSupport\Str;

abstract class Maker extends Command
{
    use CommentMakerTrait, InsertMethodTrait, TemplatesTrait, InsertUseTrait;

    /**
     *
     */
    const LARA_MAKE = 'lara-make:';

    /**
     *
     */
    const ConfigMakesPath = 'lara_maker.makes';

    /**
     *  type must be class, trait, interface or abstract class
     *
     * @var string
     */
    protected $type;


    /**
     * The path of project
     *
     * @var string
     */
    protected $rootPath;

    /**
     * Class, Traits, Interfaces root namespace
     *
     * @var string
     */
    protected $rootNameSpace;

    /**
     * @var
     */
    protected $patternNameSpace;

    /**
     * @var
     */
    protected $currentPattern;

    /**
     * The class, interface, or traits descriptive name which must be created
     *
     * @var
     */
    protected $instance;

    /**
     * The template must be end
     *
     * @var string
     */
    protected $suffix = '';

    /**
     * Make BaseTemplate which all templates must be extend
     *
     * @var
     */
    protected $makeBase;

    /**
     * The prefix of parent which all templates must be extend
     *
     * @var string
     */
    protected $basePrefix = 'Base';

    /**
     * @var
     */
    protected $parent;

    /**
     * @var string
     */
    protected $_config = '_config';

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @var
     */
    protected $stubContent;

    /**
     * Maker constructor.
     * @param Filesystem $files
     * @throws LaraCommandException
     */
    public function __construct(Filesystem $files)
    {
        $this->_configure();
        $this->files = $files;
        parent::__construct();
    }

    /**
     *
     */
    protected function _configure()
    {
        $this->name = self::LARA_MAKE . $this->name;
    }

    /**
     * @return bool
     * @throws LaraCommandException
     */
    public function fire()
    {
        $name = $this->getNameInput();
        $name = $this->fixName($name);

        if ($name == $this->_config) {
            if(method_exists($this, 'makeBasedConfig')) {
                return $this->makeBasedConfig();
            }
            $message = sprintf('%s must be contain makeBasedConfig method', get_class($this));
            throw new LaraCommandException($message);
        }

        if ($name == '_db') {
            if(method_exists($this, 'makeBasedDb')) {
                return $this->makeBasedDb();
            }
            $message = sprintf('%s must be contain makeBasedDb method', get_class($this));
            throw new LaraCommandException($message);
        }

        $patterns = explode(',', $name);
        $patterns = array_unique($patterns);
        if ($this->makeBase) {
            $this->fixParent();
        }

        return $this->fillPatterns($patterns);
    }

    protected function fillPatterns($patterns)
    {
        foreach ($patterns as $pattern) {
            $pattern = $this->fixNameSuffix($pattern);
            $pattern = $this->getPatternFullName($pattern);
            $path = $this->getPath($pattern);
            $this->setPatternNamespace($pattern);

            if ($this->files->exists($path)) {
                $message = sprintf('This %s class already exists do you want to override it', $pattern);
                if (!$this->confirm($message)) {
                    continue;
                }
            } else {
                $this->makeDirectory($path);
                $this->composerDumpAutoload();
            }

            $this->makePattern($pattern, $path);
        }
        return true;
    }

    /**
     *
     */
    protected function composerDumpAutoload()
    {
        $composer = app(Composer::class);
        $composer->dumpAutoloads();
    }

    /**
     * @return string
     */
    protected function getNameInput()
    {
        return trim($this->argument('name'));
    }

    /**
     * @param $name
     * @return string
     */
    protected function fixName($name)
    {
        $name = str_replace('/', ' ', $name);
        $name = str_replace(DIRECTORY_SEPARATOR, ' ', $name);
        $names = explode(' ', $name);
        $arr = [];

        foreach ($names as $name) {
            $arr[] = ucfirst($name);
        }

        return implode(DIRECTORY_SEPARATOR, $arr);
    }

    /**
     * @param $name
     * @return bool
     */
    protected function fixNameSuffix($name)
    {
        if (!empty($this->suffix) && !ends_with($name, $this->suffix)) {
            return $this->correctedMessageConfirm($name);
        }

        return $name;
    }

    /**
     * @return bool
     */
    protected function fixParent(){
        $basePattern = $this->getBasePatternFullName();
        $fixConfigPath = 'lara-make.fix_parent.' . $this->instance;
        if (Config::get($fixConfigPath)) {
            return;
        }

        $path = $this->getPath($basePattern);

        if ($this->files->exists($basePattern)) {
            $message = sprintf('This %s class already exists do you want to override it', $basePattern);
            if (!$this->confirm($message)) {
                return false;
            }
        } else {
            $this->makeDirectory($path);
        }

        $this->makePattern($basePattern, $path);
        $this->parent = $this->getBasePatternFullName();
        Config::set($fixConfigPath, true);
    }

    /**
     * @param $pattern
     */
    public function setPatternNamespace($pattern)
    {
        $this->patternNameSpace = $this->getNamespace($pattern);
    }

    /**
     * @param $name
     * @return string
     */
    protected function getPath($name)
    {
        if (starts_with($name, $this->rootNameSpace)) {
            $name = str_replace($this->rootNameSpace, '', $name);
        }

        return base_path($this->rootPath . DIRECTORY_SEPARATOR . $name) . '.php';
    }

    /**
     * @param string $question
     * @param bool $default
     * @return bool
     */
    public function confirm($question, $default = true)
    {
        return parent::confirm($question, $default);
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
     * @param $pattern
     * @param $path
     */
    protected function makePattern($pattern, $path)
    {
        $makes = config(self::ConfigMakesPath, []);
        $makes[] = $pattern;
        Config::set(self::ConfigMakesPath, $makes);
        $this->currentPattern = $pattern;
        $this->stubContent = $this->getStubContent();
        $this->fixStubContentFor($pattern);
        $result = $this->files->put($path, $this->stubContent);

        $this->info($pattern . ' ' . $this->instance . ' created successfully.');
    }

    /**
     * @param $pattern
     * @return string
     */
    protected function getPatternFullName($pattern)
    {
        $pattern = $this->fixName($pattern);

        if (!empty($this->rootNameSpace) && !starts_with($pattern, $this->rootNameSpace . DIRECTORY_SEPARATOR)) {
            return $this->rootNameSpace . DIRECTORY_SEPARATOR . $pattern;
        }

        return $pattern;
    }

    /**
     * @param $name
     * @param string $corrected
     * @return bool
     */
    protected function correctedMessageConfirm($name, $corrected = '')
    {
        //TODO improve message
        $message = "'%s' class, which should be make by %s command must be ends with %s, do you wont to continue";
        $message = sprintf($message, $name, get_class($this), $this->suffix);

        if (!$this->confirm($message)) {
            return $name;
        }

        if (empty($corrected)) {
            $corrected = $this->getCorrectedPatternName($name);
        }

        $message = "Do you want to create corrected suffix '%s' class, or make your input '%s' class.";
        $message .= "For make corrected '%s' class press Yes";
        $message = sprintf($message, $corrected, $name, $corrected);

        if ($this->confirm($message)) {
            return $corrected;
        }

        return $name;
    }

    protected function getCorrectedPatternName($pattern)
    {
        return ends_with($pattern, $this->suffix) ? $pattern : $pattern . $this->suffix;
    }

    protected function makeType($type, $name = '')
    {
        $instance = $this->getApplication()->find(self::LARA_MAKE . $type);

        if (empty($name)) {
            $name = $instance->getCorrectedPatternName($this->clearSuffix($this->getNameInput()));;
        }

        $instanceFullName = $instance->getPatternFullName($name);

        if (!in_array($instanceFullName, config(self::ConfigMakesPath))) {
            $this->call(self::LARA_MAKE . $type, ['name' => $name]);
            //TODO
            $this->composerDumpAutoload();
        }

        return $instanceFullName;
    }


    /**
     * @param $pattern
     */
    protected function fixStubContentFor($pattern)
    {
        $this->insertStubNamespace($pattern);
        $this->insertStubPatternType();
        $this->insertStubPattern($pattern);
        $this->insertStubParent();

        //Only properties traits uses logic is other type
        if (method_exists($this, 'insertStubProperties')) {
            $this->insertStubProperties();
        }
        $this->insertStubMethods();

        $this->stubContent = str_replace(';' . PHP_EOL . '_use', ';', $this->stubContent);
        $this->stubContent = str_replace('_use' . PHP_EOL . PHP_EOL, '', $this->stubContent);
        $this->stubContent = str_replace(' _parent', '', $this->stubContent);
        $this->stubContent = str_replace(' _interface', '', $this->stubContent);
        $this->stubContent = str_replace(TAB . '_trait' . PHP_EOL . PHP_EOL, '', $this->stubContent);
        $this->stubContent = str_replace(TAB . '_property' . PHP_EOL . PHP_EOL, '', $this->stubContent);
    }

    /**
     * @return string
     */
    protected function getStubContent()
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR  . 'stubs' . DIRECTORY_SEPARATOR . 'pattern.stub';
        return $this->files->get($path);
    }

    /**
     * @param $pattern
     */
    protected function insertStubNamespace($pattern)
    {
        $namespace = $this->getNamespace($pattern);
        if (!empty($namespace)) {
            $this->stubContent = str_replace('_namespace', 'namespace '. $namespace . ';', $this->stubContent);
        } else {
            $this->stubContent = str_replace('_namespace' . PHP_EOL. PHP_EOL, '', $this->stubContent);
        }
    }

    /**
     *
     */
    protected function insertStubPatternType()
    {
        $this->stubContent = str_replace('_type', $this->type, $this->stubContent);
    }

    /**
     * @param $pattern
     */
    protected function insertStubPattern($pattern)
    {
        $this->stubContent = str_replace('_pattern', class_basename($pattern), $this->stubContent);
    }

    /**
     *
     */
    protected function insertStubParent()
    {
        if (!empty($this->parent)) {
            $parent = $this->insertStubUse($this->parent);
            $this->stubContent = str_replace('_parent', 'extends ' . $parent, $this->stubContent);
        } else {
            $this->stubContent = str_replace('_parent', '', $this->stubContent);
        }
    }

    /**
     * @param $pattern
     * @return string
     */
    protected function getNamespace($pattern)
    {
        if (is_object($pattern)) {
            $pattern = get_class($pattern);
        }

        if (!str_contains($pattern, DIRECTORY_SEPARATOR)) {
            return '';
        }

        return Str::before($pattern, DIRECTORY_SEPARATOR);
    }

    /**
     * @param $pattern
     * @return string
     */
    protected function clearSuffix($pattern) {
        return str_replace_last($this->suffix, '', $pattern);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the pattern'],
        ];
    }

    /**
     * @return string
     */
    protected function getBasePatternFullName()
    {
        return $this->getPatternFullName($this->basePrefix . $this->instance);
    }
}
