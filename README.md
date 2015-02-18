# alpha 
Alpha is a lean framework for building WEB Applications/API's (*MVC* pattern).

**(work in progress - webapp folder is an example of how to use in a project)**


--

* **Default Routing** : http://example.com/{s:controller}/{s:action}/{i:id}

* **Controller** :
  * Names should be like **User***Controller*
  
  * Define **HTTP methods** by prefixing the *Controller* *Actions* with the type, e.g. :

    * **GET** http://example.com/User/edit/123 --> ```UserController->getEdit(...);```
    * **GET** http://example.com/User/search?name=john --> ```UserController->getSearch(...);```
    * **POST** http://example.com/User/edit/123 --> ```UserController->getEdit(...);```
    * **DELETE** http://example.com/User/delete/123 --> ```UserController->delete(...);```

  * **Inject data** into the controller actions :
  
    * Parameters from **URI path** : ```$PATH_parameter_name```
    * Parameters from **QueryString** : ```$QUERY_parameter_name```
    * Parameters from **Request** : ```$PARAM_parameter_name```
    * Parameters from **Session** : ```$SESSION_parameter_name```
    * Parameters from **Cookie** : ```$COOKIE_parameter_name```
      * e.g. : ```UserController->postEdit($PATH_id, $PARAM_name, $PARAM_age);```
      * e.g. : ```UserController->getSearch($QUERY_name);```

**Views**

  * Assign data in *Controller* :

      ```
      $this->data['property_name'] = 'data';
      ```

  * Iterate a list :

      ```
	@foreach(list)
		content
	@/foreach(list)
      ```
  * Include a view inside another view :

      ```
      @include("/path/to/html/from/webapp/view")
      ```
	
  * Use a view as a base for another view :

      ```
      @uses("/path/to/html/from/webapp/view")
      ```

  * Define/Override a section:

      ```
      @section(name)
            content 
      @/section(name)
      ```
	
  * Output a translated key :

      ```
      @string(key)
      ```
	
  * Output the value of a property :

      ```
      @(property_name)
      ```
	
  * Conditionally output the value of a property :
   ```
	      @([property_name=value])
		      content
	      @/([property_name=value])
      ```
    
**Model**
