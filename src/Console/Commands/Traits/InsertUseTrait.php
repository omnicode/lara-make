<?php

namespace LaraMaker\Console\Commands\Traits;

use LaraSupport\Str;

trait InsertUseTrait
{
    /**
     * @param $pattern
     * @param string $prefix
     * @return string
     */
    protected function insertStubUse($pattern, $prefix = 'Old')
    {
        // TODO check interface, class, traits exists
        $usePart = Str::before($this->stubContent, '_use');
        $_pattern  = class_basename($pattern);
        //To check class exist or already makes with package
        $makes = config(self::ConfigMakesPath);
        if (!str_contains($usePart, $pattern) && Str::before($pattern, DIRECTORY_SEPARATOR) != $this->patternNameSpace) {
            if (str_contains($usePart, $_pattern . ';')) {
                $_pattern = $prefix . $_pattern;
                $pattern = $pattern . ' as ' . $_pattern;
            }
            $this->stubContent = str_replace('_use', $this->useTemplate($pattern)  . '_use', $this->stubContent);
        }

        return $_pattern;
    }

    protected function useTemplate($pattern)
    {
            return  sprintf('use %s;%s', $pattern, PHP_EOL);
    }
}
