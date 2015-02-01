# alpha (Work in progress)

Alpha is a lean framework for building web applications (*MVC* pattern).

--

* **Default Routing** : http://example.com/{s:controller}/{s:action}/{i:id}

* **Controller** :
  * Names should be like **User***Controller*
  
  * Define **HTTP methods** by prefixing the *Controller* *Actions* with the type :
  
    * e.g. : **GET**  http://example.com/User/edit/123 is done by ```UserController->getEdit(...);```
    * e.g. : **GET**  http://example.com/User/search?name=john is done by ```UserController->getSearch(...);```
    * e.g. : **POST** http://example.com/User/edit/123 is done by ```UserController->getEdit(...);```
    * e.g. : **DELETE** http://example.com/User/delete/123 is done by ```UserController->delete(...);```

  * **Injecting data** into the controller actions :
  
    * Parameters from **URI path** : ```$PATH_parameter_name```
    * Parameters from **QueryString** : ```$QUERY_parameter_name```
    * Parameters from **Request** : ```$PARAM_parameter_name```
      * e.g. : ```UserController->postEdit($PATH_id, $PARAM_name, $PARAM_age);```
      * e.g. : ```UserController->getSearch($QUERY_name);```
