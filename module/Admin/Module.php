<?php
namespace Admin;

use Admin\Auth\MvcAuthEvent;
use Admin\Auth\MvcRouteListener;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface
{
    protected $services;

    public function onBootstrap(MvcEvent $e)
    {
        $app      = $e->getApplication();
        $events   = $app->getEventManager();
        $this->services = $app->getServiceManager();

        $authentication = $this->services->get('authentication');
        $authorization = null;
        $mvcAuthEvent = new MvcAuthEvent(
            $e,
            $authentication,
            $authorization
        );

        $routeListener = new MvcRouteListener(
            $mvcAuthEvent,
            $events
        );

        /*$events->attach(
            MvcAuthEvent::EVENT_AUTHENTICATION,
            $this->services->get('Admin\Auth\Authentication\DefaultAuthenticationListener')
        );

        $events->attach(
            MvcAuthEvent::EVENT_AUTHORIZATION,
            $this->services->get('Auth\Authorization\DefaultResourceResolverListener'),
            1000
        );*/
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return include __DIR__ . '/config/services.config.php';
    }

    public function getControllerConfig()
    {
        return include __DIR__ . '/config/controllers.config.php';
    }
}
