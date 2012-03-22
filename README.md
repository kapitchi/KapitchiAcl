[WIP] Zend Framework 2 - Kapitchi ACL module
=================================================
Version: 0.1  
Author:  [Matus Zeman (matuszemi)](https://github.com/matuszemi)  

Introduction
============
Provides ACL and different types of "guards" for your application.

The module is still in experimental phase but it's working for me ;) - if you have any questions feel free to contact me anytime.

Skype: matuszemi  
Email: matus.zeman@gmail.com  
IRC: matuszemi @ FreeNode / #zftalk.2  

Features
========

* Acl management (Config adapter - using module.config.php)
  * Roles (including hierarchies) [COMPLETE]
  * Resources (including hierarchies) [COMPLETE]
  * Allow/deny rules [COMPLETE]
* Guards
  * Route - protects Mvc routes [COMPLETE]
  * Event - protects events [COMPLETE]     
* Db adapters (Zend\Db) for all above [NOT STARTED]
* Config -> Db sync [NOT STARTED]

Requirements
============

* [Zend Framework 2](https://github.com/zendframework/zf2) (latest master)
* [KapitchiBase](https://github.com/kapitchi/KapitchiBase) (latest master)

Usage
=====
You can manage roles, resources and what rules is loaded into ACL using module.config.php file (DI configuration) from your module.
The module comes with few pre-defined roles/resources/rules (see [module config](https://github.com/kapitchi/KapitchiAcl/blob/master/config/module.config.php)).
ACL module depends on other modules in order to provide role of currently logged in user otherwise it defaults to _guest_ role.
See an example in [KapitchiIdentity module - KapitchiAcl plugin](https://github.com/kapitchi/KapitchiIdentity/blob/master/src/KapitchiIdentity/Plugin/KapitchiAcl.php).

Roles, resources and rules
--------------------------
ACL module introduces following common roles below. There are no specific permissions set for them as it should be responsibility of your modules to do so.

* guest - anonymous/non authenticated user 
* auth - authenticated user but with no local user reference 
* user (parent: auth) - authenticated user with local user reference 
* admin (parent: auth) - can be used to define admininistrator user permissions

The idea behind _auth_ role is that some applications might not need to manage users locally (so there is no local user reference/id known) but they still want users to be authenticated to unlock few parts of the application.
This can be used to render few extra "social" blocks on you site while you're authenticated using Facebook Connect. In this case you might want to consider creating new role auth/facebook and set Facebook related permissions to this role.
_user_ and _admin_ roles are considered as having local user reference managed by your user/authentication module.
They have got _auth_ parent role so generally speaking whatever _auth_ user can do _user_ and _admin_ users are allowed to do also.


### Acl configuration
ACL can be fully configured from module config using DI settings of [AclLoaderConfig mapper](https://github.com/kapitchi/KapitchiAcl/blob/master/src/KapitchiAcl/Model/Mapper/AclLoaderConfig.php).
Nice example can be found in [KapitchiIdentity module](https://github.com/kapitchi/KapitchiIdentity/blob/master/config/module.config.php) - search for "KapitchiAcl\Model\Mapper\AclLoaderConfig".
The mapper reads config array defining roles/resources/rules in the structure below.

#### Config example
```
File: MyModule/config/module.config.php

$aclConfig = array(
    'roles' => array(
        'role1' => null,
        'role2_with_one_parent' => 'user',
        'role3_with_multiple_parents' => array('guest', 'auth'),
    )
    'resources' => array(
        'parent' => array(
            'child1' => null,
            'child2_with_more_children' => array(
                'grandchild1' => null,
                'grandchild2' => null,
            ),
        ),
    ),
    'rules' => array(
        'allow' => array(
            //grand admin all privileges on any resource
            'allow_rule_unique_identifier' => array('admin', null),
            //grand user all privileges on child1 resource
            'allow_rule_unique_identifier2' => array('user', 'child1'),
            //grand user persist privilege to both grandchild1 and grandchild2 resources
            'allow_rule_unique_identifier3' => array('user', array('grandchild1', 'grandchild2'), 'persist'),
            //grand role1 remove and create privileges on parent resource 
            'allow_rule_unique_identifier4' => array('role1', 'parent', array('remove', 'create')),
        ),
        'deny' => array(
            //same format as for allow rules
        ),
    ),
)

return array(
    'di' => array(
        'instance' => array(
            'KapitchiAcl\Model\Mapper\AclLoaderConfig' => array(
                'parameters' => array(
                    'config' => $aclConfig
                 )
            )
        )
    )
);   
```


Guards
------
Guards "protects" different aspects of the application from being accessible by unauthorized users.
If unauthorized user tries to access e.g. route they are not permitted to [Unauthorized exception](https://github.com/kapitchi/KapitchiAcl/blob/master/src/KapitchiAcl/Exception/UnauthorizedException.php) is thrown.  
They have been two guards implemented so far: Route and Event guards.

TBD: Should we return HttpResponse instead of throwing Unauthorized exception?

### Route guard
[Route guard](https://github.com/kapitchi/KapitchiAcl/blob/master/src/KapitchiAcl/Guard/Route.php) is used to protect MVC routes. The guard configuration maps route into ACL route resource. Route resource ACL can be then configured as any other resource permissions.

The guard is attached to Zend\Mvc\Application.dispatch event at priority 1000.

#### Config example

Zend\Mvc\Router\RouteStack route configuration:

* MyModule
    * ChildRoute1
    * ChildRoute2
        * GrandChildRoute1
        * GrandChildRoute2   

See [ZF2 MVC Routing manual](http://packages.zendframework.com/docs/latest/manual/en/zend.mvc.routing.html) for more details or [KapitchiIdentity module example](https://github.com/kapitchi/KapitchiIdentity/blob/master/config/module.config.php).


```
File: MyModule/config/module.config.php


$routeResourceMapConfig = array(
    'default' => 'Route' //sets default route resource - any unresolved routes defaults to 'Route' resource
    'child_map' => array(
        'MyModule' => array(
            //sets 'MyModule/Route' resource being default resource for all child routes under MyModule route
            'default' => 'MyModule/Route',
            'child_map' => array(
                //sets 'MyModule/Route/ChildRoute1' resource for 'ChildRoute1' route
                'ChildRoute1' => 'MyModule/Route/ChildRoute1',
                'ChildRoute2' => array(
                    'default' => 'MyModule/Route/ChildRoute2'
                    'child_map' => array(
                        'GrandChildRoute1' => 'MyModule/Route/ChildRoute2/GrandChild1',
                        'GrandChildRoute2' => 'MyModule/Route/ChildRoute2/GrandChild2',
                     )
                 )
             )
        )
    )
);

$aclConfig = array(
    'resources' => array(
        'Route' => array(
            'MyModule/Route' => array(
                'MyModule/Route/ChildRoute1' => null,
                'MyModule/Route/ChildRoute2' => array(
                    'MyModule/Route/ChildRoute2/GrandChild1' => null,
                    'MyModule/Route/ChildRoute2/GrandChild2' => null,
                ),
            ),
        )
    ),
    'rules' => array(
        'allow' => array(
            //grand admin access permission to all pages/routes
            'allow/default_route' => array('admin', 'Route'),
            //grand user access permission to all pages under MyModule routes
            'MyModule/allow/route2' => array('user', 'MyModule/Route'),
         ),
        'deny' => array(
            //restrict user to access all pages under MyModule/Route/ChildRoute1 and MyModule/Route/ChildRoute2/GrandChild1 routes
            'MyModule/deny/restrict_user' => array('user', array('MyModule/Route/ChildRoute1', 'MyModule/Route/ChildRoute2/GrandChild1')),
         ),
    ),
);

return array(
    'di' => array(
        'instance' => array(
            'KapitchiAcl\Model\Mapper\AclLoaderConfig' => array(
                'parameters' => array(
                    'config' => $aclConfig
                 )
            ),
            'KapitchiAcl\Model\Mapper\RouteResourceMapConfig' => array(
                'parameters' => array(
                    'config' => $routeResourceMapConfig
                 )
            )
        )
    )
);   

```                                                  

### Event guard

[Event guard](https://github.com/kapitchi/KapitchiAcl/blob/master/src/KapitchiAcl/Guard/Event.php) protects (at priority 1000) all events specified in the configuration. Similar to Route guard, Event guard maps event identifier (object which triggers the event - a target) and event name to a resource and privilege optionally.
Resource role permissions can be then adjusted as needed in ACL configuration.

Example of common usage can be an authorizing certain roles to perform application service operations.
E.g. you can specify that only _admin_ role can _persist_ _MyModule\Model\Album_ model. The condition obviously is to trigger an event before insert/update operation from a service method.

The guard is attached to all events specified in the configuration at priority 1000. We use StaticEventManager to attach listeners to events.

```
File: MyModule/config/module.config.php


$eventGuardDefMapConfig = array(
    'MyModule/Model/Album.get' => array(
        'eventId' => 'MyModule\Service\Album',
        'event' => 'get.load',
        'resource' => 'MyModule/Model/Identity',
        'privilege' => 'get',
    ),
    'MyModule/Model/Album.persist' => array(
        'eventId' => 'MyModule\Service\Album',
        'event' => 'persist.pre',
        'resource' => 'MyModule/Model/Identity',
        'privilege' => 'persist',
    ),
    'MyModule/Model/Album.remove' => array(
        'eventId' => 'MyModule\Service\Album',
        'event' => 'remove.pre',
        'resource' => 'MyModule/Model/Identity',
        'privilege' => 'remove',
    ),
);

$aclConfig = array(
    'resources' => array(
        'MyModule/Model/Identity' => null
    ),
    'rules' => array(
        'allow' => array(
            //grand admin a permission to perform any operation on MyModule/Model/Album resource
            'MyModule/allow/admin' => array('admin', 'MyModule/Model/Album'),
            //grand user a permission to read/get MyModule/Model/Album resource but they can't persist/remove
            'MyModule/allow/user' => array('user', 'MyModule/Model/Album', 'get'),
         ),
    ),
);

return array(
    'di' => array(
        'instance' => array(
            'KapitchiAcl\Model\Mapper\AclLoaderConfig' => array(
                'parameters' => array(
                    'config' => $aclConfig
                 )
            ),
            'KapitchiAcl\Model\Mapper\EventGuardDefMapConfig' => array(
                'parameters' => array(
                    'config' => $eventGuardDefMapConfig
                 )
            )
        )
    )
);   


```