<?php

namespace LaraMake\Console\Commands;

use Illuminate\Support\Arr;
use function LaraMake\aaaa;
use LaraMake\Exceptions\LaraCommandException;
use function LaraMake\lara_json_decode;
use function LaraMake\lara_json_encode;
use function LaraMake\lara_maker_array_decode;
use function LaraMake\lara_maker_array_encode;
use LaraSupport\Str;

class Parser
{
    const NOT_DATA = '__not__data__';


    /**
     * @var array
     */
    private $arrayInputConfig = [
        'starts' => '[',
        'ends' => ']',
        'delimiter' => ','
    ];


    /**
     * @var string
     */
    protected $dynamicParseInputStructure = 'parse%sInput';

    /**
     * @param $key
     * @param $value
     * @return array|bool
     */
    public function parseInput($key, $value)
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_array($value)) {
            // @TODO
            dd('@TODO');
        }

        $starts = $this->arrayInputConfig['starts'];

        if (starts_with($value, $starts)) {
            return lara_maker_array_decode($value);
        }

        return $value;
    }

    /**
     * @param $attribute
     * @param string $data
     * @param string $separator
     * @param string $suffix
     * @param int $depth
     * @param string $prefix
     * @return string
     */
    public function parseAttribute($attribute, $data = self::NOT_DATA, $separator = '=', $suffix = '', $depth = 0, $prefix = '$', $isAssoc = false)
    {
        if ('$' == $prefix  && !str_contains($attribute, ' ')) {
            $attribute = '$' . $attribute;
        }
        if($data === self::NOT_DATA) {
            return $attribute . $suffix;
        }

        if (is_array($data)) {
            return $this->parseArrayAttribute($attribute, $data, $separator, $suffix, $depth, $prefix, $isAssoc);
        }

        if ($isAssoc) {
            return "$attribute $separator " . $this->fixValue($data) . $suffix;
        }  else {
            return $this->fixValue($data) . $suffix;
        }
    }

    protected function fixValue($value) {

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

    public function parseArrayAttribute($attribute, $data, $separator = '=', $suffix = '', $depth = 0, $prefix = '$', $isAssoc = false)
    {
        $result = $attribute . ' ' .$separator . ' [';

        if (empty($data)) {
            $this->fixDepth($result,$depth);
            $result .= PHP_EOL;
        } else {
            $isAssoc = Arr::isAssoc($data);
            foreach ($data as $attribute => $value) {
                $this->fixDepth($result,$depth);
                $result .= $this->parseAttribute("'$attribute'", $value, '=>', ',', $depth + 1, '', $isAssoc);
            }
        }

        if ($depth) {
            $this->fixDepth($result, $depth - 1);
        }
        $result .= ']' . $suffix;
        return $result;
    }


    protected function fixDepth(&$string, $depth = false)
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
    public function parseReflectionParameter($parameter)
    {
        $parameterString = $parameter->__toString() . PHP_EOL;
        $parameterString = Str::between($parameterString, '>', ']');
        return trim($parameterString);
    }
}