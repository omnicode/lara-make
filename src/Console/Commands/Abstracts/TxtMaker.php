<?php

namespace LaraMake\Console\Commands\Abstracts;

abstract class TxtMaker extends BaseMaker
{
    /**
     * @var string
     */
    public $stub = 'txt.stub';

    /**
     * @var string
     */
    public $extension = '.txt';

    public $processKeyWordBackSlashParts = [

    ];
}
