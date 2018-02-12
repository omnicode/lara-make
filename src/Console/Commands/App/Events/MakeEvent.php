<?php

namespace LaraMaker\Console\Commands\App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use LaraMaker\Console\Commands\Abstracts\ClassMaker;
use LaraMaker\Console\Commands\App\LaraAppClassMaker;
use LaraMaker\Console\Commands\Traits\InsertMethodsDirectlyTrait;

class MakeEvent extends ClassMaker
{
    use InsertMethodsDirectlyTrait;

    /**
     * @var string
     */
    protected $name = 'event';

    /**
     * @var string
     */
    protected $instance = 'Event';

    /**
     * @var string
     */
    protected $rootPath = 'app' . DIRECTORY_SEPARATOR . 'Events';

    /**
     * @var string
     */
    protected $rootNameSpace = 'App' . DIRECTORY_SEPARATOR . 'Events';

    /**
     * @var string
     */
    protected $description = 'Make flexible Events';

    /**
     * @var bool
     */
    protected $makeBase = true;

    /**
     * @var array
     */
    protected $interfaces = [
    ];

    /**
     * @var array
     */
    protected $traits = [
        InteractsWithSockets::class,
        SerializesModels::class
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
                'content' => "return new PrivateChannel('channel-name');"
            ]
        ]
    ];

    protected function insertStubMethods()
    {
        $this->insertStubUse(PrivateChannel::class);
        parent::insertStubMethods();
    }


}