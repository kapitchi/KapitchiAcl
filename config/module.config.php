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
                            'Route/Default' => null,//used by KapitchiAcl\Guard\Route
                        ),
                        'roles' => array(
                            'guest' => null,
                            'auth' => null,
                            'user' => 'auth',
                            'admin' => 'auth',
                        ),
                        'rules' => array(
                            'allow' => array(
                                'allow/default_route' => array(array('auth', 'guest'), 'Route/Default'),
                            )
                        )
                    ),
                ),
            ),
            'KapitchiAcl\Guard\Route' => array(
                'parameters' => array(
                    'aclService' => 'KapitchiAcl\Service\Acl',
                    'routeResourceMap' => array(
                        'default' => 'Route/Default',
                    )
                )
            ),
            'KapitchiAcl\Guard\Event' => array(
                'parameters' => array(
                    'aclService' => 'KapitchiAcl\Service\Acl',
                )
            ),
        ),
    ),
);
