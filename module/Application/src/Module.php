<?php

namespace Application;

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

        // CORS headers
        $eventManager->attach(MvcEvent::EVENT_FINISH, function (MvcEvent $event) {
            $response = $event->getResponse();
            if (!$response) return;

            $headers = $response->getHeaders();
            $headers->addHeaderLine('Access-Control-Allow-Origin', '*');
            $headers->addHeaderLine('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
            $headers->addHeaderLine('Access-Control-Allow-Headers', 'Content-Type, Authorization, Accept');
            $headers->addHeaderLine('Access-Control-Max-Age', '86400');

            $request = $event->getRequest();
            if ($request->getMethod() === 'OPTIONS') {
                $response->setStatusCode(200);
                $response->setContent('');
            }
        });
    }

    public function getServiceConfig(): array
    {
        return [
            'factories' => [],
        ];
    }

    public function getControllerConfig(): array
    {
        return [
            'factories' => [],
        ];
    }
}
