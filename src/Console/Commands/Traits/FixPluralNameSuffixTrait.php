<?php

namespace LaraMake\Console\Commands\Traits;

use LaraSupport\Str;

trait FixPluralNameSuffixTrait
{
    /**
     * @param $name
     * @return bool
     */
    protected function fixNameSuffix($name)
    {
        if (!empty($this->suffix)) {
            if (ends_with($name, $this->suffix)) {
                $_name = Str::before($name, $this->suffix);

                if ($_name == str_plural($_name)) {
                    return $name;
                }

                $corrected = str_replace_first($_name, str_plural($_name), $name);
            } else {
                $corrected = str_plural($name) . $this->suffix;
            }

            return $this->correctedMessageConfirm($name, $corrected);
        }

        return $name;
    }


}
