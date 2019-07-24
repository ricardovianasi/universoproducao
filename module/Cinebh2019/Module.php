<?php
namespace Cinebh2019;

use Zend\Mvc\Controller\AbstractController;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $app      = $e->getApplication();
        $eventManager   = $app->getEventManager();
        $serviceManager = $app->getServiceManager();
        $events = $eventManager->getSharedManager();

        /** @var $translator \Zend\I18n\Translator\Translator */
        $translator = $serviceManager->get('translator');
        $locale     = new \Locale();

//        $lacaleParam = $e->getRouteMatch()->getParam('locale');

        $httplanguages = getenv('HTTP_ACCEPT_LANGUAGE');
        if (empty($httplanguages) && isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $httplanguages = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        }

        $langCode = $e->getRouter()->match($e->getRequest()) ?  $e->getRouter()->match($e->getRequest())->getParam('locale') : null;
        if($langCode == 'en')
            $translatorLocale = 'en_US';
        elseif($langCode == 'pt')
            $translatorLocale = 'pt_BR';
        else
            $translatorLocale = 'pt_BR';

        $translator->setLocale($locale->acceptFromHttp($httplanguages))->setFallbackLocale($translatorLocale);
        $locale->setDefault($translator->getLocale());

        $events->attach(AbstractController::class, 'dispatch', function($e) use ($langCode) {
            $controller = $e->getTarget();
            $controller->layout()->locale = $langCode;
        }, 100);

        // Route translator
        $eventManager->attach('route', array($this, 'onPreRoute'), 100);
    }

    /**
     * @param $e \Zend\Mvc\MvcEvent
     */
    public function onPreRoute($e)
    {
        /** @var $application \Zend\Mvc\Application */
        $application    = $e->getApplication();
        $serviceManager = $application->getServiceManager();
        $serviceManager->get('router')->setTranslator($serviceManager->get('translator'));
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
