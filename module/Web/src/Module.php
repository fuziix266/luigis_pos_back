<?php

namespace Web;

use Laminas\Mvc\MvcEvent;

class Module
{
    public function getConfig(): array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e): void
    {
        $app = $e->getApplication();
        $eventManager = $app->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();

        // Cambiar layout para las acciones del módulo Web
        $sharedEventManager->attach(
            'Laminas\Mvc\Controller\AbstractActionController',
            MvcEvent::EVENT_DISPATCH,
            function (MvcEvent $event) {
                $controller = $event->getTarget();
                $controllerClass = get_class($controller);

                // Solo aplicar a controladores del módulo Web
                if (str_starts_with($controllerClass, 'Web\\')) {
                    $event->getViewModel()->setTemplate('web/layout');
                }
            },
            100
        );
    }
}
