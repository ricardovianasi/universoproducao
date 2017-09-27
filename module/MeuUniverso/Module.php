<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace MeuUniverso;

use MeuUniverso\Auth\MvcRouteListener;
use Zend\Mvc\MvcEvent;
use Zend;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $app      = $e->getApplication();
        $events   = $app->getEventManager();
        $this->services = $app->getServiceManager();

        $authentication = $this->services->get('meuuniverso_authenticationservice');;
        $routeListener = new MvcRouteListener($events, $authentication);

        $translator = new Zend\Mvc\I18n\Translator(new \Zend\I18n\Translator\Translator());
        $translator->addTranslationFile(
            'phpArray',
            __DIR__ . '/../../vendor/zendframework/zend-i18n-resources/languages/pt_BR/Zend_Validate.php',
            'default',
            'pt_BR'
        );

        Zend\Validator\AbstractValidator::setDefaultTranslator($translator);

        //$this->bootstrapSession($e);
    }

    public function bootstrapSession($e)
    {
        $session = $e->getApplication()
            ->getServiceManager()
            ->get('meuuniverso_session');

        $session->start();
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
