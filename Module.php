<?php

namespace KapitchiAcl;

use Zend\Module\Manager,
    Zend\Mvc\AppContext as Application,
    Zend\EventManager\StaticEventManager,
    Zend\EventManager\EventDescription as Event,
    Zend\Mvc\MvcEvent as MvcEvent,
    KapitchiBase\Module\ModuleAbstract;

class Module extends ModuleAbstract {
    
    public function bootstrap(Manager $moduleManager, Application $app) {
        $locator      = $app->getLocator();
        
        $events = StaticEventManager::getInstance();
        
        //route protector
        if($this->getOption('enable_route_guard', true)) {
            $routeProtector = $locator->get('KapitchiAcl\Guard\Route');
            $app->events()->attach('dispatch', array($routeProtector, 'dispatch'), 1000);
        }
        
    }
    
    public function getDir() {
        return __DIR__;
    }
    
    public function getNamespace() {
        return __NAMESPACE__;
    }
    
}
