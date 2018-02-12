<?php

namespace LaraMake\Console\Commands\Traits;

trait CommentMakerTrait
{
    /**
     * @param $data
     * @param string $message
     * @return string
     */
    protected function getPropertyComment($data, $message = '')
    {
        $info = gettype($data);
        return $this->getComment($info, $message);
    }

    protected function getComment($info = '', $message = '', $type = \ConstCommentType::Property)
    {
        $comment = TAB .'/**' . PHP_EOL;
        $comment .= $message ? TAB . ' * ' . $message . PHP_EOL . TAB . ' * ' . PHP_EOL : '';
        $comment .= TAB . ' * @var';
        $comment .= $info ? ' ' . $info : '';
        $comment .= PHP_EOL . TAB .' */' . PHP_EOL . TAB;
        return $comment;
    }
}
