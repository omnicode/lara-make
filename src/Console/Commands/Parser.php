<?php

namespace LaraMaker\Console\Commands;

use Illuminate\Support\Arr;
use LaraSupport\Str;

class Parser
{
    const NOT_DATA = '__not__data__';

    /**
     * @param $attribute
     * @param string $data
     * @param string $suffix
     * @param string $separator
     * @return string
     */
    public static function parseAttribute($attribute, $data = self::NOT_DATA, $separator = '=', $suffix = '', $depth = 0)
    {
        if($data === self::NOT_DATA) {
            return $attribute . $suffix;
        }

        if (is_array($data)) {
            return self::parseArrayAttribute($attribute, $data, $separator, $suffix, $depth);
        }

        return "$attribute $separator" . self::fixValue($data) . $suffix;
    }

    protected static function fixValue($value) {

        if (is_null($value)) {
            return 'null';
        }

        if ($value === true) {
            return 'true';
        }

        if ($value === false) {
            return 'false';
        }

        if (is_string($value)) {
            return "'$value'";
        }

        if (is_numeric($value)) {
            return $value;
        }

        if (is_object($value)) {
            dd('New type fix it', $value);
        }

    }

    public static function parseArrayAttribute($attribute, $data, $separator = '=', $suffix = '', $depth = 0)
    {
        $result = $attribute . ' ' .$separator . ' [';

        if (empty($data)) {
            self::fixDepth($result,$depth);
            $result .= PHP_EOL;
        } else {
            foreach ($data as $attribute => $value) {
                self::fixDepth($result,$depth);
                if (is_numeric($attribute)) {
                    $result .= self::fixValue($value) . ',';
                } else {
                    $result .= self::parseAttribute("'$attribute'", $value, '=>', ',', $depth + 1);
                }
            }
        }

        if ($depth) {
            self::fixDepth($result, $depth - 1);
        }
        $result .= ']' . $suffix;
        return $result;

//        $str =  "$attribute = [";
//        foreach ($data as $key => $value) {
//            $str .=  self::parseAttribute($key, $value, $suffix, '=>');
//        }
//        $str .= "]" . $suffix;
//
//        return $str;
    }


    protected static function fixDepth(&$string, $depth = false)
    {
        if (!ends_with($string, PHP_EOL)) {
            $string .= PHP_EOL;
        }
        if ($depth) {
            for ($i = 0; $i < $depth; $i++) {
                $string .= TAB;
            }
        }
        return $string;
    }

    /**
     * Parameter #0 [ <required> LaraRepo\Criteria\Criteria $criteria ]
     */
    public static function parseReflectionParameter($parameter)
    {
        $parameterString = $parameter->__toString() . PHP_EOL;
        $parameterString = Str::between($parameterString, '>', ']');
        return trim($parameterString);
    }
}