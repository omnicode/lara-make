<?php

namespace LaraMaker\Console\Commands\App\Providers;

use App\Models\Raxations;
use LaraMaker\Console\Commands\Abstracts\ClassMaker;
use LaraMaker\Console\Commands\App\LaraAppClassMaker;
use LaraRepo\Contracts\RepositoryInterface;
use LaraRepo\Eloquent\AbstractRepository;

class MakeRepositoryServiceProvider extends ClassMaker
{
    protected $name = 'repository-service-provider';

}