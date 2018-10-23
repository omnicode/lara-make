<?php

namespace LaraMake\Console\Commands\Traits\Replace;

trait ReplacePropertyTrait
{
    /**
     * @param $content
     * @param $keyWord
     * @param $value
     * @return mixed
     */
    public function replacePropertyKeyWord($content, $keyWord, $value)
    {
        $str = '';
        $property = (array) $value;
        if (!empty($property)) {
            $str = $this->insertProperties($property);
        }

        if (empty($str)) {
            return str_replace(PHP_EOL .TAB . $keyWord . PHP_EOL, '', $content);
        }

        $str = rtrim($str, PHP_EOL . PHP_EOL . TAB);
        return str_replace($keyWord, $str, $content);
    }

    /**
     * @param $properties
     * @return string
     */
    protected function insertProperties($properties)
    {
        $str = '';
        foreach ($properties as $type => $propertiesData) {
            foreach ($propertiesData as $property => $value) {
                if (is_numeric($property)) {
                    $property = $value;
                    $value = null;
                }
                $str .= $this->insertPropertyBased($type, $property, $value);
            }
        }
        return $str;
    }

    /**
     * @param $type
     * @param $property
     * @param null $value
     * @return string
     */
    protected function insertPropertyBased($type, $property, $value = null)
    {
        $propertyStr = $type . ' ';
        $propertyStr .= $this->parser->parseAttribute($property, $value, '=', ';', 2, '$', true);
        $propertyStr .= PHP_EOL . PHP_EOL . TAB;
        $dept = 4;
        $numbers = 100;

        for ($j = 2; $j < $dept; $j++) {
            for ($i = 0; $i < $numbers; $i++) {
                $str = PHP_EOL;
                for ($k = 0; $k < $j; $k++) {
                    $str .= TAB;
                }
                $part = "'" . $i . "'" . ' => ';
                if (str_contains($propertyStr, $str . $part)) {
                    $propertyStr = str_replace($str . $part, $str, $propertyStr);
                } else {
                    break;
                }
            }
        }

        return $propertyStr;
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