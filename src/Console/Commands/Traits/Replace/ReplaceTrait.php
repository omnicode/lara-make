<?php

namespace LaraMake\Console\Commands\Traits\Replace;

use LaraMake\Exceptions\LaraCommandException;

trait ReplaceTrait
{

    /**
     * All keyword which stubs must be contain
     * All keywords starts wrap in keyWordStructure
     * Keys for dynamically change stub content
     *
     * Also for dynamically replace keywords define "replaceExampleKeyWord($content, $keyword)"
     * modify keywords and return result {not null}
     *
     * for example if $keywords = ['_name'] make "replaceNameKeyWord($content, $keyword)"
     * where as argument pass stubContent  and keyword must be _name
     *
     * @var array
     */
    public $keyWords = [];

    /**
     * @var string
     */
    public $keyWordStructure = '{{%s}}';

    /**
     * For change method nAme kayWords
     *
     * @var string
     */
    protected $dynamicReplaceMethodStructure = 'replace%sKeyWord';

    /**
     * @var string
     */
    protected $dynamicKeyWordStructure = 'get%sKeyWordTemplate';

    /**
     * @var string
     */
    protected $dynamicTrimMethodStructure = 'trim%sKeyWord';

    /**
     * For manually insert keyWord value when keyword options not exists
     * for example namespace
     *
     * @var string
     */
    protected $dynamicGetKeyWordPropertyValue = 'get%sKeyWordPropertyValue';

    /**
     * @param $content
     * @return mixed
     * @throws LaraCommandException
     */
    protected function replaceStubContent($content)
    {
        $keyWords = array_keys($this->keyWords);
//        $makes = config(self::CONFIG_MAKES_PATH, []);
//        $makes[] = $pattern;
//        Config::set(self::CONFIG_MAKES_PATH, $makes);
        foreach ($keyWords as $keyWord) {
            $method = sprintf($this->dynamicReplaceMethodStructure, $this->ucFirstCamelCase($keyWord));
            $keyTemplateMethod = camel_case(sprintf($this->dynamicKeyWordStructure, $this->ucFirstCamelCase($keyWord)));
            $keyOption = '__' . $keyWord;
            if (in_array($keyWord, $this->notNeedOptions)) {
                $setKeyWordProperty = sprintf($this->dynamicGetKeyWordPropertyValue, $this->ucFirstCamelCase($keyWord));
                if (!method_exists($this, $setKeyWordProperty)) {
                    throw new LaraCommandException(sprintf(
                        'This %s class must be contain %s method',
                        get_class($this),
                        $setKeyWordProperty
                    ));
                }
                $this->{$keyOption} = $this->{$setKeyWordProperty}();
            }

            $_keyWord = $this->getStructuredKeyWord($keyWord);
            if (method_exists($this, $method)) {
                $content = $this->{$method}($content, $_keyWord, $this->{$keyOption});
                if (is_null($content)) {
                    $message = $method . ' must be return corrected content not null';
                    throw new LaraCommandException($message);
                }
            } elseif (method_exists($this, $keyTemplateMethod)) {
                $values = (array)$this->{$keyOption};
                foreach ($values as $value) {
                    $content = $this->replaceKeyWordBasedKeyWordTemplate($content, $keyWord, $value);
                }
            }
        }

        // @TODO dry
        foreach ($keyWords as $keyWord) {
            $method = sprintf($this->dynamicTrimMethodStructure, $this->ucFirstCamelCase($keyWord));
            $_keyWord = $this->getStructuredKeyWord($keyWord);
            if (method_exists($this, $method) && str_contains($content, $_keyWord)) {
                $content = $this->{$method}($content, $_keyWord);
                if (is_null($content)) {
                    $message = $method . ' must be return corrected content not null';
                    throw new LaraCommandException($message);
                }
            }
        }

        foreach ($keyWords as $keyWord) {
            $content = str_replace($this->getStructuredKeyWord($keyWord), '', $content);
        }

        return $this->trimFinalContent($content);
    }

    /**
     * @TODO change name
     *
     * @param $content
     * @param $keyWord
     * @param $value
     * @return string
     */
    public function replaceKeyWordBasedKeyWordTemplate($content, $keyWord, $value)
    {
        $keyTemplateMethod = camel_case(sprintf($this->dynamicKeyWordStructure, $this->ucFirstCamelCase($keyWord)));
        $_keyWord = $this->getStructuredKeyWord($keyWord);
        $template = $this->{$keyTemplateMethod}($_keyWord);
        $change = sprintf($template, $value);
        return str_replace_first($_keyWord, $change, $content);
    }

    /**
     * Overwrite ths methods for fix final changes
     *
     * @param $content
     * @return mixed
     */
    public function trimFinalContent($content)
    {
        return str_replace("$'", "'", $content);
    }

    /**
     * @param $keyWord
     * @return string
     */
    public function getStructuredKeyWord($keyWord)
    {
        return sprintf($this->keyWordStructure, $keyWord);
    }

    /**
     * @param $value
     * @return string
     */
    public function ucFirstCamelCase($value)
    {
        return ucfirst(camel_case($value));
    }

}
