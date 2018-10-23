<?php

namespace LaraMake;

use LaraMake\Exceptions\LaraCommandException;
use LaraSupport\Str;

function lara_maker_array_encode($value, $options = 0, $depth = 512)
{
    $json = json_encode($value, $options, $depth);
    return str_replace(['{', '}', ':', '"', ' ', ';'], ['[', ']', '=', '', '___', '____'], $json);
}

function lara_maker_array_decode($json)
{
    $json = str_replace(['____', '___'], [';', ' '], $json);
    if ('[]' == $json) {
        return [];
    }

    $json = str_replace_first('[', '', $json);
    $json = str_replace_last(']', '', $json);
    $result = [];
    if (starts_with($json, '[')) {
        $result[] = lara_maker_array_decode($json);
        return $result;
    }
    do {
        $commaPos = strpos($json, ',');
        $equalPos = strpos($json, '=');
        if (false !== $commaPos && false != $equalPos) {
            if ($commaPos > $equalPos) {
                $key = Str::before($json, '=', 1);
                $json = Str::after($json, '=');
                if (starts_with($json, '[')) {
                    $result[$key] = Str::before($json, ',', 1);
                    $endPos = get_position($json);
                    if (false == $endPos) {
                        throw new LaraCommandException('incorrect maker array format' . $json);
                    }

                    $value = substr($json, 0, $endPos + 1);
                    $json = substr($json, $endPos + 1, strlen($json) - $endPos);
                    $json = trim($json, ',');
                    $result[$key] = lara_maker_array_decode($value);
                } else {
                    $value = Str::before($json, ',', 1);
                    $json = Str::after($json, ',');
                    $result[$key] = $value;
                }
            } else {
                $value = Str::before($json, ',' ,1);
                $json = Str::after($json, ',');
                $result[] = $value;
            }
        } else {
            if (false !== $commaPos) {
                $value = Str::before($json, ',', 1);
                $json = Str::after($json, ',');
                $result[] = $value;
            } elseif (false !== $equalPos) {
                $key = Str::before($json, '=', 1);
                $value = Str::after($json, '=');
                $result[$key] = starts_with($value, '[')
                    ? lara_maker_array_decode($value)
                    : Str::after($json, '=');
                $json = '';
            } else {
                $result[] = $json;
                $json = '';
            }
        }
    }
    while (!empty($json));
    return $result;
}

function get_position($json)
{
    $startPositions = Str::positions($json, '[');
    $endPositions = Str::positions($json, ']');

    if (count($startPositions) != count($endPositions)) {
        return false;
    }

    foreach ($endPositions as $position => $endPosition) {
        $nextPosition = $position + 1;
        if (isset($startPositions[$nextPosition])) {
            if ($startPositions[$nextPosition] > $endPosition) {
                return $endPosition;
            }
            continue;
        } else {
            return $endPosition;
        }
    }
    return false;
}