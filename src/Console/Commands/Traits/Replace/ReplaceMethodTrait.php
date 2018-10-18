<?php

namespace LaraMake\Console\Commands\Traits\Replace;

use LaraMake\Console\Commands\Abstracts\TInterfaceMaker;
use LaraMake\Console\Commands\Parser;
use LaraMake\Exceptions\LaraCommandException;
use function LaraMake\lara_maker_array_encode;

trait ReplaceMethodTrait
{
//        $methods = [
//            'public' => [
//                [
//                    'name' => '__construct',
//                    'arguments' => ['name', 'str' => 'sas'],
//                    'content' => 'return true;'
//                ]
//            ]
//        ];


    /**
     * @param $content
     * @param $keyWord
     * @param $value
     * @return mixed
     */
    public function replaceMethodKeyWord($content, $keyWord, $value)
    {
        $str = '';
        $methods = (array) $value;

        if (!empty($methods)) {
            $str = $this->insertMethods($methods);
        }

        if (empty($str)) {
            return str_replace(PHP_EOL .TAB . $keyWord . PHP_EOL, '', $content);
        }

        $str = rtrim($str, PHP_EOL . PHP_EOL . TAB);
        return str_replace(TAB . $keyWord, $str, $content);
    }


    /**
     * @param $methods
     * @return string
     */
    protected function insertMethods($methods)
    {
        $str = '';
        foreach ($methods as $type => $_methods) {
//            if (is_a($this, TInterfaceMaker::class) && $type != 'public') {
//                $message = "The '%s' method contains no public key which is not compatible in interface. Fix it in '%s' class";
//                $message = sprintf($message, '$methods', get_class($this));
//                throw new LaraCommandException($message);
//            }

            foreach ($_methods as $methodData) {
                $str .= $this->insertMethodBased($type, $methodData);
            }
        }
        return $str;
    }

    /**
     * @param $type
     * @param $data
     * @throws LaraCommandException
     */
    protected function insertMethodBased($type, $data)
    {
        if (empty($data['name'])) {
            $message = sprintf("The '%s' attribute must be contain function_type => arrays. Each array is sets of array ".
                ". Each array must be have 'name' key. Fix this standards in '%s' class", '$methods', get_class($this));
            throw new LaraCommandException($message);
        }

        $name = $data['name'];
        $arguments = !empty($data['arguments']) ? $data['arguments'] : [];

        $content = !empty($data['content']) ? $data['content'] : '';;
        return $this->methodTemplate($type, $name, $arguments, $content);
    }

    protected function fixMethodArguments($arguments)
    {
        if (empty($arguments)) {
            return '';
        }

        $argumentContents = [];
        foreach ($arguments as $argument => $data) {
            if (is_numeric($argument)) {
                if (is_string($data) && str_contains($data, ' ')) {
                    $arr = explode(' ', $data);
                    $class = $arr[0];
                    // check for type hinting
                    if (class_exists($class)) {
                        $class = $this->insertStubUse($class);
                    }
                    $class = class_basename($class);
                    $data = $class . ' ' . $arr[1];
                }
                $argumentContents[] = $this->parser->parseAttribute($data);
            } else {
                $argumentContents[] = $this->parser->parseAttribute($argument, $data);
            }
        }
        return implode(', ', $argumentContents);

    }

    protected function fixMethodContent($content)
    {
        return trim($content);
    }

    /**
     * @param $contents
     * @return string
     */
    protected function methodContentRowTemplate($contents)
    {
        if (!is_array($contents)) {
            $contents = [$contents];
        }

        $str = '';

        foreach ($contents as  $content) {
            $str .= sprintf('%s%s%s;%s',TAB, TAB, $content, PHP_EOL);
        }

        return $str;
    }
}