# alpha 
Alpha is a lean framework for building WEB Applications/API's (*MVC* pattern).

--
### Setup

--
1. Create a virtual host based on the vhost.conf located in the samples folder.
2. Download/clone the repository and define the webapp folder as the document root of the vhost.
3. Create your controllers/models/views inside the webapp folder.

--
### How does alpha work?

* The default routing of the requests is : http://example.com/{s:controller}/{s:action}/{i:id}

* **Controllers**
 
   * Must be named like **User***Controller*
    
   * Must extend **\Alpha\Web\ControllerAbstract** or **\Alpha\Web\CrudBaseController**
   
   * Actions must be prefixed by the HTTP method it handles, e.g. :

       * **GET** http://example.com/User/edit/123 --> ```UserController->getEdit(...);```
       * **GET** http://example.com/User/search?name=john --> ```UserController->getSearch(...);```
       * **POST** http://example.com/User/edit/123 --> ```UserController->getEdit(...);```
       * **DELETE** http://example.com/User/delete/123 --> ```UserController->delete(...);```

   * Context data is captured and injected into the Actions :
  
       * Parameters from **URI path** : ```$PATH_parameter_name```
       * Parameters from **QueryString** : ```$QUERY_parameter_name```
       * Parameters from **Request** : ```$PARAM_parameter_name```
       * Parameters from **Session** : ```$SESSION_parameter_name```
       * Parameters from **Cookie** : ```$COOKIE_parameter_name```
         * e.g. : ```$UserController->postEdit($PATH_id, $PARAM_name, $PARAM_age);```
         * e.g. : ```$UserController->getSearch($QUERY_name);```

   * Passing data from Actions into the views :
         ```
         $this->data['property_name'] = 'data';
         ```
         
* **Views**
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
  * Define/override a section from a view:
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
    
* **Models**
 
   * Must extend **\Alpha\Storage\BucketAbstract**
   * Must implement the method *getSchema()*, e.g.:
   
      ```
public static function getSchema()
{
            return array(
                  'bucket' => 'user',
                  'key'    => 'id',
                  'fields' => array(
                              'id' => array('type' => 'integer'),
                              'name' => array('type' => 'string'),
                             )
                  );
}
```

--

### Do you still have questions?

Please check the samples folder at : https://github.com/pintorafael/alpha/tree/master/samples
