# alpha 

Alpha is a lean framework for building WEB Applications/API's based on the *MVC* pattern. The main goal of this framework is to provide the developer to write as little code as possible, preferrably doing one line coding (ambitious right?).


## Contents 

 * [Setup](#setup)
 * [How does alpha work](#how-does-alpha-work)
 * [Controllers](#controllers-go-to-samples)
 * [Views](#views-go-to-samples)
 * [Models](#models-go-to-samples)
 * [Connectors](#connectors-go-to-sample)
 * [Localization/i18n](#localizationi18n-go-to-sample)
 * [Configuration](#configuration)


## Setup

1. Create a virtual host based on the vhost.conf located in the samples folder.
2. Download/clone the repository and define the webapp folder as the document root of the vhost.
3. Create your controllers/models/views inside the webapp folder.


## How does alpha work?

* The *default routing* of the requests is http://example.com/{s:controller}/{s:action}/{i:id}.

      e.g. http://example.com/User/edit/123

* Each *Controller* has a set of *Actions* which handle the requests.
 
      e.g. ```$UserController->getEdit(...);```
   
* Each *Action* will result in a *Response*.

* The *Response* can be a *View* or any other type of a representation (e.g. json).

* If no *View* exists for the *Action* nor a specified *Response*, the default will be a *Json Response*.

* *Views* are html files with special tags that allow data binding from the *Controllers*.

* The *Views* should be located in the following structure ```webapp/view/{controller}/{action}.html```
   
      e.g. ```webapp/view/user/getEdit.html```


## Controllers [(go to samples)](https://github.com/pintorafael/alpha/tree/master/samples/controller)

   * Must be named like **User***Controller*.
    
   * Must extend **\Alpha\Web\ControllerAbstract** or **\Alpha\Web\CrudBaseController**.
   
   * Actions must be prefixed by the HTTP method it handles, e.g. :

       * **GET** http://example.com/User/edit/123 --> ```UserController->getEdit(...);```
       
       * **GET** http://example.com/User/search?name=john --> ```UserController->getSearch(...);```
       
       * **POST** http://example.com/User/edit/123 --> ```UserController->postEdit(..);```
       
       * **DELETE** http://example.com/User/delete/123 --> ```UserController->delete(...);```

   * Context data is captured and injected into the Actions :
  
       * Parameters from **URI path** : ```$PATH_parameter_name```
        
          e.g. : ```$UserController->getEdit($PATH_id);```
          
       * Parameters from **QueryString** : ```$QUERY_parameter_name```
         
          e.g. : ```$UserController->getSearch($QUERY_name);```
       
       * Parameters from **Request** : ```$PARAM_parameter_name```
       
          e.g. : ```$UserController->postEdit($PATH_id, $PARAM_name, $PARAM_age);``` 
       
       * Parameters from **Session** : ```$SESSION_parameter_name```
         
          e.g. : ```$UserController->getLogin($SESSION_userid);```
       
       * Parameters from **Cookie** : ```$COOKIE_parameter_name```
       
          e.g. : ```$UserController->getOther($COOKIE_userid);```
         
       * To retrieve all parameters from the context use simply the ${CONTEXT} without the parameter_name.
        
          e.g. : ```$PATH``` or ```$QUERY``` or ```$PARAM``` or ```$SESSION``` or ```$COOKIE```
       
         
         

   * Passing data from Actions into the views :
         ```
         $this->data['property_name'] = 'data';
         ```

         
## Views [(go to samples)](https://github.com/pintorafael/alpha/tree/master/samples/view)

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
 
    
## Models [(go to samples)](https://github.com/pintorafael/alpha/tree/master/samples/model)

   * Must extend **\Alpha\Storage\BucketAbstract**.
   * Must implement the method **getSchema()**, e.g.:
   
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


## Connectors [(go to sample)](https://github.com/pintorafael/alpha/blob/master/webapp/connectors.ini)

Connectors work like plugins, you have to configure them to use them.
To configure them just fill in the connectors.ini in the webapp folder.

```
    [Repo]
    host     = "localhost"
    port     = 3306
    user     = "root"
    password = "password"
    database = "tests"

    [Language]
    language = pt
```

Examples of connectors :
   * MySQL repository *(Repo)*
   * Language repository *(Language)*
   * View *(View)*


## Localization/i18n [(go to sample)](https://github.com/pintorafael/alpha/blob/master/webapp/language.ini)

The strings that must be adjusted to the location(i18n) should be located at ```webapp/language.ini``` and must have the following structure :
```
[language_code]
    key=value
```
* To use inside a *Controller* or other class just use ```Connectors::get('Language')->getString($key)```
* To use inside a *View* just use ```@string(key)```
   

## Configuration 

The configuration file should be locatedt at ```webapp/configuration.ini``` and must have the following structure :
```
[section]
    key=value
```

* To use inside a *Class* simply call ```Config::get($section, $key);```.

## Do you still have any questions?

Please check the samples folder at : https://github.com/pintorafael/alpha/tree/master/samples or send me an email to santospinto.rafael@gmail.com.

Cheers!!!
