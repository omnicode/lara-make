<?php

namespace LaraMake\Console\Commands\Traits;

use LaraMake\Console\Commands\Parser;

trait InsertPropertyTrait
{
    /**
     * @var array
     */
    protected $properties = [];

    /**
     *
     */
    protected function insertStubProperties()
    {
        if (!empty($this->properties)) {
            $this->insertProperties($this->properties);
        }
        $this->stubContent = str_replace(TAB . '_property' . PHP_EOL . PHP_EOL, '', $this->stubContent);
    }

    /**
     * @param $properties
     */
    protected function insertProperties($properties)
    {
        foreach ($properties as $type => $propertiesData) {
            foreach ($propertiesData as $property => $value) {
                if (is_numeric($property)) {
                    $property = $value;
                    $value = null;
                }
                $this->insertPropertyBased($type, $property, $value);
            }
        }
    }

    /**
     * @param $property
     * @param $type
     * @param null $value
     */
    protected function insertPropertyBased($type, $property, $value = null)
    {
        $comment = $this->getPropertyComment($value);
        $propertyStr = $type . ' ';
        $propertyStr .= Parser::parseAttribute($property, $value, '=', ';', 2);

        $propertyStr .= PHP_EOL . TAB;
        $this->stubContent = str_replace(TAB . '_property', $comment . $propertyStr . PHP_EOL . TAB . '_property', $this->stubContent);
    }

    /**
     * @param $data
     * @return string
     */
    protected function getArrayStructure($data)
    {
        $str = ' = [' . PHP_EOL;
        foreach ($data as $key => $value) {
            if (!is_numeric($key)) {
                if (is_array($value)) {
                    //TODO fix
                    dd('TODO');
                } else {
                    if (is_string($value)) {
                        $str .= TAB . TAB . "'$key' => '$value'," . PHP_EOL;
                    } else {
                        $str .= TAB . TAB . "'$key' => ";
                        $str .= ($value === true) ? 'true' : 'false';
                        $str .= ',' .  PHP_EOL;
                    }
                }
            } else {
                //TODO fix [47 => 2]
                //TODO fix [\ConstCommentType::Property => 2]
                if (is_numeric($value)) {
                    $str .= TAB . TAB . "'$value',".  PHP_EOL;
                } else {
                    $str .= TAB . TAB . "'$key' => '$value',".  PHP_EOL;
                }
            }
        }
        $str .= TAB . ']';
        return $str;
    }


}