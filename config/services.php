<?php

use App\EventListener\ExceptionListener;
use App\Service\Task\TaskService;
use App\Service\Task\TaskServiceInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function(ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $container->parameters()->set('env(APP_DEBUG)', "0");
    $container->parameters()->set('app.debug', '%env(APP_DEBUG)%');

    // makes classes in src/ available to be used as services
    // this creates a service per class whose id is the fully-qualified class name
    $services->load('App\\', '../src/')
        ->exclude('../src/{DependencyInjection,Entity,Kernel.php}');

    $services->set(ExceptionListener::class)
        ->tag('kernel.event_listener', ['method' => 'onKernelException']);
    $services->alias(TaskServiceInterface::class, TaskService::class);
};
