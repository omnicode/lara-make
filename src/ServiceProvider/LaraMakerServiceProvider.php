<?php
namespace LaraMaker\ServiceProvider;

use LaraMaker\Console\Commands\App\Console\Commands\MakeClassCommand;
use LaraMaker\Console\Commands\App\Console\Commands\MakeInterfaceCommand;
use LaraMaker\Console\Commands\App\Console\Commands\MakeTraitCommand;
use LaraMaker\Console\Commands\App\Events\MakeEvent;
use LaraMaker\Console\Commands\App\Http\Controllers\MakeController;
use LaraMaker\Console\Commands\App\Http\Controllers\MakeCrudController;
use LaraMaker\Console\Commands\App\Http\Middlewares\MakeMiddleware;
use LaraMaker\Console\Commands\App\Http\ViewComposers\MakeViewComposer;
use LaraMaker\Console\Commands\App\Jobs\MakeJob;
use LaraMaker\Console\Commands\App\Listeners\MakeListener;
use LaraMaker\Console\Commands\App\Models\MakeModel;
use LaraMaker\Console\Commands\App\Providers\MakeRepositoryServiceProvider;
use LaraMaker\Console\Commands\App\Repositories\Eloquent\MakeRepository;
use LaraMaker\Console\Commands\App\Repositories\Contracts\MakeRepositoryInterface;
use LaraMaker\Console\Commands\App\Services\MakeService;
use LaraMaker\Console\Commands\App\Validators\MakeValidator;
use LaraMaker\Console\Commands\LaraClassMaker;
use LaraMaker\Console\Commands\LaraInterfaceMaker;
use LaraMaker\Console\Commands\LaraTraitMaker;
use LaraSupport\LaraServiceProvider;

class LaraMakerServiceProvider extends LaraServiceProvider
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

