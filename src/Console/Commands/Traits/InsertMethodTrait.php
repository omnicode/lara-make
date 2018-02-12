<?php

namespace LaraMaker\Console\Commands\Traits;

use LaraMaker\Console\Commands\Parser;
use LaraMaker\Exceptions\LaraCommandException;

trait InsertMethodTrait
{
    /**
     * Pattern methods
     *
     * @var array
     */
    protected $methods = [];

    /**
     *
     */
    protected function insertStubMethods()
    {
        if (!empty($this->methods)) {
            $this->insertMethods($this->methods);
            $this->stubContent = str_replace(TAB . PHP_EOL . TAB . '_method' . PHP_EOL , '', $this->stubContent);
        } else {
            $this->stubContent = str_replace(TAB . PHP_EOL . TAB . PHP_EOL . TAB . '_method' . PHP_EOL , '', $this->stubContent);
            $this->stubContent = str_replace(TAB . PHP_EOL . TAB . '_method' . PHP_EOL , '', $this->stubContent);
            $this->stubContent = str_replace('_method', '', $this->stubContent);
        }
    }

    /**
     * @param $methods
     * @throws LaraCommandException
     */
    protected function insertMethods($methods)
    {
        foreach ($methods as $type => $_methods) {
            if ($this->type == 'interface' && $type != 'public') {
                $message = "The '%s' method contains no public key which is not compatible in interface. Fix it in '%s' class";
                $message = sprintf($message, '$methods', get_class($this));
                throw new LaraCommandException($message);
            }

            foreach ($_methods as $methodData) {
                $this->insertMethodBased($type, $methodData);
            }
        }
    }

    /**
     * @param $type
     * @param $data
     * @throws LaraCommandException
     */
    protected function insertMethodBased($type, $data)
    {
        if (empty($data['name'])) {
            $message = sprintf("The '%s' attribute must be contain function_type => arrays. Each array is sets of array ".
                ". Each array must be have 'name' key. Fix this standards in '%s' class", '$methods', get_class($this));
            throw new LaraCommandException($message);
        }
        $name = $data['name'];
        $arguments = !empty($data['arguments']) ? $data['arguments'] : [];
        $content = $this->getMethodContentBy($data);
        $method = $this->methodTemplate($type, $name, $arguments, $content);
        $this->stubContent = str_replace(TAB . '_method', $method . TAB . '_method', $this->stubContent);
    }

    /**
     * @param $data
     * @return array
     */
    protected function getMethodContentBy($data)
    {
        return !empty($data['content']) ? $data['content'] : '';
    }

    protected function fixMethodArguments($arguments)
    {
        if (empty($arguments)) {
            return '';
        }

        $argumentContents = [];
        foreach ($arguments as $argument => $data) {
            if (is_numeric($argument)) {
                if (is_string($data) && str_contains($data, ' ')) {
                    $arr = explode(' ', $data);
                    $class = $arr[0];
                    // check for type hinting
                    if (class_exists($class)) {
                        $class = $this->insertStubUse($class);
                    }
                    $class = class_basename($class);
                    $data = $class . ' ' . $arr[1];
                }
                $argumentContents[] = Parser::parseAttribute($data);
            } else {
                $argumentContents[] = Parser::parseAttribute($argument, $data);
            }
        }
        return implode(', ', $argumentContents);

    }

    protected function fixMethodContent($content)
    {
        return trim($content);
    }



    /**
     * @param $contents
     * @return string
     */
    protected function methodContentRowTemplate($contents)
    {
        if (!is_array($contents)) {
            $contents = [$contents];
        }

        $str = '';

        foreach ($contents as  $content) {
            $str .= sprintf('%s%s%s;%s',TAB, TAB, $content, PHP_EOL);
        }

        return $str;
    }


}