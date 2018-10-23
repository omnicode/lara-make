<?php

namespace LaraMake\Console\Commands\Traits;

use LaraMake\Console\Commands\Abstracts\TInterfaceMaker;

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

        if (is_a($this, TInterfaceMaker::class)) {
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
