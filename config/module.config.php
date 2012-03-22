<?php
return array(
    'KapitchiAcl' => array(
        'options' => array(
            'enable_cache' => false,
            'enable_guards' => array(
                'route' => true,
                'event' => true,
            )
        ),
    ),
    'di' => array(
        'instance' => array(
            'KapitchiAcl\Service\Acl' => array(
                'parameters' => array(
                    'aclLoader' => 'KapitchiAcl\Model\Mapper\AclLoaderConfig',
                ),
            ),
            'KapitchiAcl\Model\Mapper\AclLoaderConfig' => array(
                'parameters' => array(
                    'config' => array(
                        'resources' => array(
                            //'Route' => null,//used by KapitchiAcl\Guard\Route
                        ),
                        'roles' => array(
                            'guest' => null,
                            'auth' => null,
                            'user' => 'auth',
                            'admin' => 'auth',
                        ),
                        'rules' => array(
                            'allow' => array(
                                //'allow/default_route' => array(array('auth', 'guest'), 'Route/Default'),
                            )
                        )
                    ),
                ),
            ),
            'KapitchiAcl\Guard\Route' => array(
                'parameters' => array(
                    'aclService' => 'KapitchiAcl\Service\Acl',
                    'routeResourceMapMapper' => 'KapitchiAcl\Model\Mapper\RouteResourceMapConfig',
                )
            ),
            'KapitchiAcl\Model\Mapper\RouteResourceMapConfig' => array(
                'parameters' => array(
                    'config' => array(
                        //'default' => 'Route',
                    )
                )
            ),
            'KapitchiAcl\Guard\Event' => array(
                'parameters' => array(
                    'aclService' => 'KapitchiAcl\Service\Acl',
                    'eventGuardDefMapper' => 'KapitchiAcl\Model\Mapper\EventGuardDefMapConfig',
                )
            ),
        ),
    ),
);
