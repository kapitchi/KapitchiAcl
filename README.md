[WIP] Zend Framework 2 - Kapitchi ACL module
=================================================
Version: 0.1
Author:  Matus Zeman (matuszemi)

Introduction
------------
Provides ACL

Features
--------
* Acl management (Config adapter - using module.config.php)
  * Roles (including hierarchies) [COMPLETE]
  * Resources (including hierarchies) [COMPLETE]
  * Allow/deny rules [COMPLETE]
* Guards - protecting different aspects of the application from being accessible by unauthorized users
  * Route - protects Mvc routes [COMPLETE]
  * Event - protects events in the system [COMPLETE]     
* Db adapters (Zend\Db) for all above [NOT STARTED]

Requirements
------------

* [Zend Framework 2](https://github.com/zendframework/zf2) (latest master)
* [KapitchiBase](https://github.com/matuszemi/KapitchiBase) (latest master)

Usage
-----
You can manage roles, resources and what rules is loaded into ACL using module.config.php file (DI configuration) from your module.
The module come with few pre-defined roles/resources/rules (see [module config](https://github.com/matuszemi/KapitchiAcl/blob/master/config/module.config.php)).

Roles:
* guest - anonymous/non authenticated user 
* auth - authenticated user but with no local user reference 
* user - authenticated user with local user reference 
* admin - can be used to define admininistrator user permissions

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
