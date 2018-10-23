<?php
namespace LaraMake\ServiceProvider;

use LaraMake\Console\Commands\App\Controllers\MakeViewComposer;
use LaraMake\Console\Commands\App\Controllers\MakeController;
use LaraMake\Console\Commands\App\MakeAll;
use LaraMake\Console\Commands\App\Models\MakeModel;
use LaraMake\Console\Commands\App\Providers\MakeRepositoryServiceProvider;
use LaraMake\Console\Commands\App\Repositories\MakeRepository;
use LaraMake\Console\Commands\App\Repositories\MakeRepositoryInterface;
use LaraMake\Console\Commands\App\Models\MakeValidator;
use LaraMake\Console\Commands\App\Services\MakeService;
use LaraMake\Console\Commands\LaraClassMaker;
use LaraMake\Console\Commands\LaraInterfaceMaker;
use LaraMake\Console\Commands\Makers\LaraCrudConfigMaker;
use LaraMake\Console\Commands\Makers\RouteMaker;
use LaraSupport\LaraServiceProvider;

class LaraMakeServiceProvider extends LaraServiceProvider
{

    public function boot()
    {
    }

    public function register()
    {
//        $this->registerConstants(__DIR__, 'class_constants.php');
        $this->registerConstants(__DIR__);
        $this->registerFunctions(__DIR__);
    }
}
