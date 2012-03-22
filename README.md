[WIP] Zend Framework 2 - Kapitchi ACL module
=================================================
Version: 0.1
Author:  [Matus Zeman (matuszemi)](https://github.com/matuszemi)

Introduction
============
Provides ACL

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
This can be used to show few extra "social" blocks on you site while you're authenticated using Facebook Connect. In this case you might want to consider creating new role auth/facebook and set Facebook related permissions to this role.
_user_ and _admin_ roles are considered as having local user reference managed by your user/authentication module.
They have got _auth_ parent role so generally speaking whatever _auth_ user can do _user_ and _admin_ users are allowed to do also.

Only resource defined is _Route/Default_ used by Route Guard (see below). By default _guest_ and _auth_ user role (thus _user_ and _admin_) can access any route. See [module config](https://github.com/kapitchi/KapitchiAcl/blob/master/config/module.config.php) for details.

### Acl configuration example


Guards
------
Guards "protects" different aspects of the application from being accessible by unauthorized users.
If unauthorized user tries to access e.g. route they are not permitted to [an exception](https://github.com/kapitchi/KapitchiAcl/blob/master/src/KapitchiAcl/Exception/UnauthorizedException.php) is thrown.  
They have been two guards implemented so far: Route and Event guards.

### Route guard
Route guard is used to protect MVC routes. The guard configuration maps route into ACL resource while the resource can be then configured using 

TODO

```
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
```                                                  


TODO