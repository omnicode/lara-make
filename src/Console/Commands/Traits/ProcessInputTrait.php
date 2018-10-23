<?php

namespace LaraMake\Console\Commands\Traits;

use function LaraMake\lara_maker_array_decode;
use function LaraMake\lara_maker_array_encode;

trait ProcessInputTrait
{
    /**
     * Automatically confirm all confirm box
     *
     * @var
     */
    protected $__confirm;

    /**
     * @var
     */
    protected $__confirmBackSlashes;

    /**
     * Force overwrite existing file content
     *
     * @var
     */
    protected $__confirmOverwrite;

    /**
     * Enable to choice default
     *
     * @var
     */
    protected $__choiceDefault;

    /**
     * Choice count with choice, after it work not continued
     *
     * @var int
     */
    public $choiceCount = 3;

    /**
     * For emphasis user input start input
     *
     * @var string
     */
    public $attentionStructure = '"%s"';

    /**
     * All parts of input must be process by method
     *
     * keyword => method
     *
     * @TODO, validate
     * @var string
     */
    public $processKeyWordBackSlashParts = [
        'pattern' => 'ucfirst'
    ];

    /**
     * All input as whole expect suffix part must be process by method
     *
     * keyword => method
     *
     * @TODO, validate
     * @var string
     */
    public $processKeyWord = [
    ];

    /**
     * All keyword must be end in this suffix if there partially match in suffix latest $checkSuffixLength count
     *
     * @var string
     */
    public $processKeyWordSuffix = [

    ];

    /**
     * It must be checked if partially suffix ends match the initial letters
     *
     * @var int
     */
    protected $checkSuffixLength = 3;


    /**
     * @var string
     */
    protected $dynamicProcessInputStructure = 'process%sInput';


    /**
     * @param $key
     * @param $value
     * @return array|string
     */
    public function processInput($key, $value)
    {
        if (is_array($value)) {
            $values = [];
            foreach ($value as $_key => $_value) {
                $values[$_key] = $this->processSingleInput($key, $_value);
            }

            return $this->finalProcessInput($key, $values);
        }

        if (is_string($value)) {
            $value = $this->processSingleInput($key, $value);
            return $this->finalProcessInput($key, $value);
        }

        return $this->finalProcessInput($key, $value);
    }

    /**
     * @param $key
     * @param $value
     * @return string
     */
    public function processSingleInput($key, $value)
    {
        if (is_array($value)) {
            // @TODO processBackSlashes,processSingleInputBackSlushParts, processSingleInputWithSuffix for nested
            return $value;
        }
        $value = $this->processBackSlashes($value);
        $value = $this->processSingleInputBackSlushParts($key, $value);
        $value = $this->processSingleInputWithSuffix($key, $value);

        return $value;
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function finalProcessInput($key, $value)
    {
        $fixKeyWordMethod = sprintf($this->dynamicProcessInputStructure, $this->ucFirstCamelCase($key));
        if (method_exists($this, $fixKeyWordMethod)) {
            return  $this->{$fixKeyWordMethod}($value);
        }

        return $value;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function fixBackSlash($name)
    {
        if (is_string($name)) {
            return $this->processBackSlashes($name);
        }

        return $name;
    }

    /**
     * @param $name
     * @return bool|mixed
     */
    public function processBackSlashes($name)
    {
        $corrected = str_replace(['/', DIRECTORY_SEPARATOR], [' ', ' '], $name);
        $corrected = str_replace(' ', DIRECTORY_SEPARATOR, $corrected);

        if ($corrected == $name) {
            return $name;
        }

        if ($this->__confirmBackSlashes) {
            return $corrected;
        }

        if ($this->confirm($this->attentionSprintF('Do you wont correct %s to %s', $name, $corrected))) {
            return $corrected;
        }

        return $name;
    }

    /**
     * @param $input
     * @param string $value
     * @return string
     */
    public function processSingleInputBackSlushParts($input, string $value)
    {
        if (empty($this->processKeyWordBackSlashParts[$input])) {
            return $value;
        }

        $words = explode(DIRECTORY_SEPARATOR, $value);
        foreach ($words as &$word) {
            // ucfirst or lcfirst or valid method name
            $method = $this->processKeyWordBackSlashParts[$input];
            $word = $method($word);
        }
        $corrected = implode(DIRECTORY_SEPARATOR, $words);
        if ($corrected != $value) {
            $message = $this->attentionSprintF(sprintf('Your initial %s input parts of backslashes correction result is %s', $value, $corrected));
            return $this->confirm($message) ? $corrected : $value;
        }

        return $value;
    }

    /**
     * @param $input
     * @param string $value
     * @return string
     */
    public function processSingleInputWithSuffix($input, string $value)
    {
        $corrected = $value;
        $suffix = $this->processKeyWordSuffix[$input] ?? '';

        if ($suffix) {
            $corrected = $this->getWithoutSuffixPart($value, $suffix);
        }

        if (!empty($this->processKeyWord[$input])) {
            $method = $this->processKeyWord[$input];
            $corrected = $method($corrected);
            if ($corrected == $value) {
                return $corrected;
            }

            $message = sprintf('Whole correction initial input without suffix %s is %s', $value, $corrected);
            $corrected  = $this->confirm($message) ? $corrected : $value;
        }

        $corrected = $corrected . $suffix;
        if ($corrected == $value) {
            return $corrected;
        }

        return $this->choiceCorrectionMessage($value, $corrected, $suffix);
    }

    /**
     * Check if pattern ends partially match with suffix it delete that part
     *
     * @param $pattern
     * @param $suffix
     * @return string
     */
    public function getWithoutSuffixPart($pattern, $suffix)
    {
        if (empty($suffix)) {
            return $pattern;
        }

        $suffixLen = strlen($suffix);
        if ($suffixLen < $this->checkSuffixLength) {
            // @TODO Dry
            $substring = substr($pattern, -strlen($suffix));
            if (ucfirst($substring) === $suffix) {
                return str_replace_last($substring, '', $pattern);
            }
        }

        for ($len = $suffixLen; $len >= $this->checkSuffixLength; $len--) {
            $partialSuffix = substr($suffix, 0, $len);
            // @TODO Dry
            $substring = substr($pattern, -strlen($partialSuffix));
            if (ucfirst($substring) === $partialSuffix) {
                return str_replace_last($substring, '', $pattern);
            }
        }

        return $pattern;
    }

    /**
     * attention sprintf wrap changeable words with  attention symbols
     *
     * @return string
     */
    public function attentionSprintF()
    {
        $arguments = func_get_args();
        $arguments[0] = str_replace('%s', $this->getAttentionString('%s'), $arguments[0]);
        return sprintf(...$arguments);
    }

    /**
     * return wrapped attention symbols
     *
     * @param $string
     * @return string
     */
    public function getAttentionString($string)
    {
        return sprintf($this->attentionStructure, $string);
    }

    /**
     * @param string $question
     * @param array $choices
     * @param null $default
     * @param null $attempts
     * @param null $multiple
     * @return null|string
     */
    public function choice($question, array $choices, $default = null, $attempts = null, $multiple = null)
    {
        if ($default && $this->__choiceDefault) {
            return $default;
        }

        return parent::choice($question, $choices, $default, $attempts, $multiple );
    }

    /**
     * @param string $question
     * @param bool $default
     * @return bool
     */
    public function confirm($question, $default = true)
    {
        if ($this->__confirm) {
            return true;
        }

        return parent::confirm($question, $default);
    }

    /**
     * Choice message to correct user input or not
     *
     * @param $pattern
     * @param $corrected
     * @param $suffix
     * @return string
     */
    protected function choiceCorrectionMessage($pattern, $corrected, $suffix)
    {
        if ($this->__choiceDefault) {
            return $corrected;
        }
        //@TODO improve message
        $message = "%s class, which should be make by %s command suggested to end with %s ." . PHP_EOL;
        $message = $this->attentionSprintF($message, $pattern, get_class($this), $suffix);
        $message .= "Do you want to create corrected suffix?";


        $choiceOne = $this->attentionSprintF('Continue with your initial %s input', $pattern);
        $choiceTwo = $this->attentionSprintF('Change your initial %s input to corrected %s and continue', $pattern, $corrected);

        $choices = [
            1 => $choiceOne,
            2 => $choiceTwo,
        ];
        $choice = $this->choice($message, $choices, 2, $this->choiceCount);

        if ($choice == $choiceTwo) {
            return $corrected;
        }

        return $pattern;
    }

}
