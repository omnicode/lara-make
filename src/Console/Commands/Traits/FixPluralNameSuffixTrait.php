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
            return $this->correctedMessageConfirm($name, false, true);
        }

        return $name;
    }


}
