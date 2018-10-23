<?php

if (!defined('TAB')) {
    define('TAB', '    ');
}

if (!class_exists('ConstCommentType')) {
    class ConstCommentType
    {
        const Property = 1;
        const Method = 2;
    }
}