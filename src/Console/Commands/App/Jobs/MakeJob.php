<?php

namespace LaraMaker\Console\Commands\App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use LaraMaker\Console\Commands\Abstracts\ClassMaker;

class MakeJob extends ClassMaker
{
    /**
     * @var string
     */
    protected $name = 'job';

    /**
     * @var string
     */
    protected $instance = 'Job';

    /**
     * @var string
     */
    protected $rootPath = 'app' . DIRECTORY_SEPARATOR . 'Jobs';

    /**
     * @var string
     */
    protected $rootNameSpace = 'App' . DIRECTORY_SEPARATOR . 'Jobs';

    /**
     * @var string
     */
    protected $description = 'Make flexible Jobs';

    /**
     * @var bool
     */
    protected $makeBase = true;

    /**
     * @var array
     */
    protected $interfaces = [
        ShouldQueue::class
    ];

    /**
     * @var array
     */
    protected $traits = [
        Queueable::class,
        SerializesModels::class,
        InteractsWithQueue::class
    ];

    /**
     * @var array
     */
    protected $properties = [

    ];

    /**
     * @var array
     */
    protected $methods = [
        'public' => [
            [
                'name' => '__constract',
            ],
            [
                'name' => 'handle',
            ]
        ]
    ];
}