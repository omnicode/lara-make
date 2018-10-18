<?php

namespace LaraMake\Console\Commands\Traits;

trait DynamicMethodExampleTrait
{
    /**
     * Firstly called this method for process "example" keyword
     *
     * @param $input
     * @return mixed
     */
    public function processExampleInput($input)
    {
        // some changes in input end return result
        return $input;
    }

    /**
     * Firstly called this method for dynamically change keyword in stub content
     *
     * @param $content
     * @param $keyWord
     * @param $input
     * @return mixed
     */
    public function replaceExampleKeyWord($content, $keyWord, $input)
    {
        // change in content $keyWord based $input , or any type and return result
        //$content = str_replace($keyWord, $input, $content);
        return $content;
    }

    /**
     * if not defined replaceExampleKeyWord method than that case you can pass structure
     * according with must be change in stub contnt
     *
     * @param $keyWord
     * @return string
     */
    public function getExampleKeyWordTemplate($keyWord)
    {
        $template = 'some %s template';
        // $template = 'return %s;';
        // $template = 'return %s;' . PHP_EOL . $keyWord; //when keyword can dynamically add new values
        return $template;
    }


    /**
     * Called this method if after replace remained $keyWord and must be trim it
     *
     * @param $content
     * @param $keyWord
     * @return mixed
     */
    public function trimExampleKeyWord($content, $keyWord)
    {
        //$content = str_replace($keyWord, '', $content);
        return $content;
    }
}
