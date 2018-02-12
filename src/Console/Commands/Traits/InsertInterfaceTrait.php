<?php

namespace LaraMaker\Console\Commands\Traits;

use Illuminate\Support\Collection;
use LaraMaker\Console\Commands\Parser;
use LaraMaker\Exceptions\LaraCommandException;
use LaraSupport\Str;

trait InsertInterfaceTrait
{
    /**
     * @var array
     */
    protected $interfaces = [];

    /**
     *
     */
    protected function insertStubInterfaces()
    {
        if (!empty($this->interfaces)) {
            $this->insertInterfaces($this->interfaces);
            $this->stubContent = str_replace(', _interface', '', $this->stubContent);
        } else {
            $this->stubContent = str_replace(' _interface', '', $this->stubContent);
        }
    }

    /**
     * @param $interfaces
     * @throws LaraCommandException
     */
    protected function insertInterfaces($interfaces)
    {
        foreach ($interfaces as $interface) {
            if (!interface_exists($interface) && !in_array($interface, config(self::ConfigMakesPath))) {
                $message = sprintf("%s interface does not exist. Fix it in '%s' class", $interface, get_class($this));
                throw new LaraCommandException($message);
            }

            $implements = str_contains($this->stubContent, ' implements ') ? '' : ' implements ';
            $_interface = $this->insertStubUse($interface);
            $this->stubContent = str_replace('_interface', $implements . $_interface. ', _interface', $this->stubContent);

            $this->insertInterfaceMethods($interface);
        }
    }

    /**
     * @param $interface
     */
    public function insertInterfaceMethods($interface = null)
    {
        $reflection = new \ReflectionClass($interface);
        $methods = $reflection->getMethods();
        $this->insertReflectionMethods($methods);
    }

    /**
     * @param $methods
     */
    public function insertReflectionMethods($methods)
    {
        $parent = $this->parent;
        $parentMethods = [];
        $parentRef = null;

        if ($parent) {
            $parentMethods = get_class_methods($parent);
            $parentRef = new \ReflectionClass($parent);
        }

        foreach ($methods as $method) {
            if (in_array($method->name, array_dot($this->methods), true)) {
                continue;
            }
            $modifier = $method->getModifiers();
            //TODO improve
//            if ($modifier != 134283522 && in_array($method->name, $parentMethods)) {
            if (in_array($method->name, $parentMethods)) {
                $parentMethod = $parentRef->getMethod($method->name);
                if ($this->getMethodData($method) == $this->getMethodData($parentMethod)) {
                    continue;
                }

                $message = "'%s' class has '%s' method which declaration is not compatible with '%s' interface. Do You want to override it!!";
                $message = sprintf($message, $parent, $method->name, $method->class);
                if (!$this->confirm($message)) {
                    continue;
                }
            }

            if (in_array($modifier, [134217986, 134283522])) {
                $modifier = 'public';
            } elseif ($modifier == 134217987) {
                $modifier = 'public static';
            } else {
                dd("TODO fix reflection method name " . $modifier);
            }
            $this->methods[$modifier][] = $this->getMethodData($method);
        }

    }

    protected function modifiers()
    {
        return [
            'public' => 134217986,
            'public static' => 134217987
        ];
    }

    /**
     * @param $method
     * @return array
     */
    public function getMethodData($method)
    {
        $parameters = $method->getParameters();
        $arguments = [];
        foreach ($parameters as $parameter) {
            $name = $this->fixParameterName($parameter);
            if ($parameter->isDefaultValueAvailable()) {
                $default = $this->fixParameterDefaultValue($parameter);
                $arguments[$name] = $default;
            } else {
                $arguments[] = $name;
            }
        }

        return [
            'name' => $method->name,
            'arguments' => $arguments
        ];
    }

    /**
     * @param $parameter
     * @return string
     */
    protected function fixParameterName($parameter)
    {
        //TODO fix <optional> LaraRepo\Criteria\Criteria or NULL $criteria = NULL
        $parameter = Parser::parseReflectionParameter($parameter);

        if (str_contains($parameter, '=')) {
            $parameter = Str::before($parameter, ' =');
        }

        $parameterArr = explode(' ', $parameter);

        if (count($parameterArr) == 1) {
            return $parameter;
        }

        $typeHint = head($parameterArr);

        if (in_array($typeHint, ['array', 'callable'])) {
            return $parameter;
        }

        if (str_contains($typeHint, DIRECTORY_SEPARATOR)) {
            $typeHint = $this->insertStubUse($typeHint);
        } else {
            $typeHint = DIRECTORY_SEPARATOR  . $typeHint;
        }

        return $typeHint  . ' ' . last($parameterArr);
    }

    /**
     * @param $parameter
     * @return array|string
     */
    protected function fixParameterDefaultValue($parameter)
    {
        if ($parameter->isDefaultValueConstant()) {
            return class_basename($parameter->getDefaultValueConstantName());
        }

        $default =  $parameter->getDefaultValue();

        if ($default === false) {
            $default  = 'false';
        } elseif ($default === true) {
            $default  = 'true';
        } elseif (is_null($default)) {
            $default  = 'null';
        } elseif (is_string($default )) {

        } elseif (is_numeric($default)) {

        } elseif (is_array($default)) {
            $default = [];
        } else {
            dd($default);
        }
        return $default;
    }

}
