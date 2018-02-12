<?php

namespace LaraMaker\Console\Commands\Traits;

trait TemplatesTrait
{
    /**
     * @param $type
     * @param $name
     * @param array $arguments
     * @param string $content
     * @return string
     */
    public function methodTemplate($type, $name, $arguments = [], $content = '')
    {
        $methodStr = TAB . $type . ' function ' . $name . '(';
        $methodStr .= $this->fixMethodArguments($arguments);
        $methodStr .= ')';

        if ($this->type === 'interface') {
            return $methodStr . ';' . PHP_EOL . TAB . PHP_EOL;
        }

        $methodStr .=  PHP_EOL . TAB . '{' . PHP_EOL . TAB . TAB ;
        $methodStr .= $this->fixMethodContent($content);

        $methodStr .= PHP_EOL . TAB . '}' . PHP_EOL;
        $methodStr .= TAB . PHP_EOL;
        return $methodStr;
    }

    protected function objectArgumentTemplate($class)
    {
        $class = class_basename($class);
        return $class . ' $' . lcfirst($class);
    }
}
