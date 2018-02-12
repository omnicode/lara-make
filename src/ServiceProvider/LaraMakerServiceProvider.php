<?php
namespace LaraMake\ServiceProvider;

use LaraMake\Console\Commands\App\Console\Commands\MakeClassCommand;
use LaraMake\Console\Commands\App\Console\Commands\MakeInterfaceCommand;
use LaraMake\Console\Commands\App\Console\Commands\MakeTraitCommand;
use LaraMake\Console\Commands\App\Events\MakeEvent;
use LaraMake\Console\Commands\App\Http\Controllers\MakeController;
use LaraMake\Console\Commands\App\Http\Controllers\MakeCrudController;
use LaraMake\Console\Commands\App\Http\Middlewares\MakeMiddleware;
use LaraMake\Console\Commands\App\Http\ViewComposers\MakeViewComposer;
use LaraMake\Console\Commands\App\Jobs\MakeJob;
use LaraMake\Console\Commands\App\Listeners\MakeListener;
use LaraMake\Console\Commands\App\Models\MakeModel;
use LaraMake\Console\Commands\App\Providers\MakeRepositoryServiceProvider;
use LaraMake\Console\Commands\App\Repositories\Eloquent\MakeRepository;
use LaraMake\Console\Commands\App\Repositories\Contracts\MakeRepositoryInterface;
use LaraMake\Console\Commands\App\Services\MakeService;
use LaraMake\Console\Commands\App\Validators\MakeValidator;
use LaraMake\Console\Commands\LaraClassMaker;
use LaraMake\Console\Commands\LaraInterfaceMaker;
use LaraMake\Console\Commands\LaraTraitMaker;
use LaraSupport\LaraServiceProvider;

class LaraMakeServiceProvider extends LaraServiceProvider
{

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([

                LaraClassMaker::class,
                LaraInterfaceMaker::class,
                LaraTraitMaker::class,
                MakeClassCommand::class,
                MakeTraitCommand::class,
                MakeInterfaceCommand::class,
                MakeValidator::class,
                MakeModel::class,
                MakeRepositoryInterface::class,
                MakeRepository::class,
                MakeRepositoryServiceProvider::class,
                MakeCrudController::class,
                MakeController::class,
                MakeViewComposer::class,
                MakeMiddleware::class,
                MakeJob::class,
                MakeEvent::class,
                MakeListener::class,
                MakeService::class
            ]);
        }
    }

    public function register()
    {
        require_once $this->getConstantsPath(__DIR__, 'class_constants.php');
        require_once $this->getConstantsPath(__DIR__);
        require_once $this->getFunctionsPath(__DIR__);
    }

}

