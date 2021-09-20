<?php

namespace LaraMake\Console\Commands\Traits;

use LaraMake\Exceptions\LaraCommandException;
use LaraSupport\Str;

trait MakeSignatureTrait
{
    /**
     * The console command name
     * automatically mast be start lara_make:  {self::LARA_MAKE}
     * fore change it command prefix you must be change const LARA_MAKE
     *
     * @var
     */
    public $commandName;

    /**
     * Add command arguments
     * All arguments must be corrected and automatically must be insert user input to _argument property
     * All property must be camelCase
     *
     * @var
     */
    public $commandArguments = [

    ];

    /**
     * Add command options
     * All options must be corrected and automatically must be insert user input to __option property
     * All property must be camelCase
     *
     * @var array
     */
    public $commandOptions = [];

    /**
     * @var
     */
    public $notNeedOptions = [];

    /**
     * @var array
     */
    protected $defaultOptions = [
        'confirm',
        'confirm-back-slash',
        'confirm-overwrite',
        'choice-default',
        'path' => 'path=',
        'root-path' => 'root-path=',
    ];

    /**
     * Default arguments
     *
     * @var array
     */
    protected $defaultArguments = [
        'pattern'
    ];

    /**
     * @var array
     */
    private $propertyWithOptions = [];

    /**
     * @var array
     */
    private $propertyWithArguments = [];

    /**
     * Force to all command starts with self::LARA_MAKE
     * Set root path
     */
    protected function setSignature()
    {
        if (empty($this->commandName)) {
            $message = $this->attentionSprintF("%s filed is required in class %s ", 'CommandName', get_class($this));
            throw new LaraCommandException($message);
        }

        $this->signature = self::LARA_MAKE . $this->commandName;

        foreach ($this->keyWords as $key => $pattern) {
            if (is_numeric($key)) {
                $this->keyWords[$pattern] = $pattern . '=';
                unset($this->keyWords[$key]);
            }
        }

        $settings = $this->getCommandSettings();

        if (!empty($settings)) {
            $this->signature .= ' {' . implode('} {', $settings) .   '}';
        }
    }

    /**
     * @return array
     */
    public function getCommandSettings()
    {
        $arguments = $this->getCommandArguments();
        $options = $this->getCommandOptions();
        $settings = array_merge(array_values($arguments), array_values($options));
        return array_unique($settings);
    }

    /**
     * @return array
     */
    public function getCommandArguments()
    {
        $defaultArguments = $this->processCommandSettings($this->defaultArguments, $this->propertyWithArguments);
        $arguments = $this->processCommandSettings($this->commandArguments, $this->propertyWithArguments);
        return array_merge($defaultArguments, $arguments);
    }

    /**
     * @return array
     */
    public function getCommandOptions()
    {
        // need
        $defaultOptions = $this->processCommandSettings($this->defaultOptions, $this->propertyWithOptions, '--');
        $keyOptions = $this->processCommandSettings($this->keyWords, $this->propertyWithOptions, '--');
        $commandOptions = $this->processCommandSettings($this->commandOptions, $this->propertyWithOptions, '--');
        $options = array_merge($keyOptions, $defaultOptions);
        $options = array_merge($options, $commandOptions);
        $this->propertyWithOptions = array_except($this->propertyWithOptions, $this->notNeedOptions);
        return array_except($options, $this->notNeedOptions);
    }

    /**
     * make command options as [$property => $option]
     *
     * @param $settings
     * @param $resultCorrection
     * @param $delimiter
     * @return array
     * @throws LaraCommandException
     */
    public function processCommandSettings($settings, &$resultCorrection, $delimiter = '')
    {
        $_settings = [];

        if (!is_array($settings)) {
            return $_settings;
        }

        foreach ($settings as $property => $setting) {
            if (is_numeric($property)) {
                if (Str::contains($setting, [':', '|'])) {
                    // @TODO fix
//                    $property = Str::before($option, ':');
                    $message = $this->attentionSprintF('%s this structure in this time not available', ':, |');
                    throw new LaraCommandException($message);
                } elseif (Str::contains($setting, ['='])) {
                    $property = Str::before($setting, '=');
                } elseif (Str::contains($setting, ['?'])) {
                    $property = Str::before($setting, '?');
                } elseif (Str::contains($setting, ['*'])) {
                    $property = Str::before($setting, '*');
                } else {
                    $property = $setting;
                }
            }

            $resultCorrection[camel_case($property)] = $property;
            $_settings[camel_case($property)] = $delimiter . $setting;
        }

        return $_settings;
    }
}
