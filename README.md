# alpha 

Alpha is a lean framework for building Web Applications / REST API's based on the *MVC* pattern. The main goal of this framework is to provide the developer to write as little code as possible, preferrably doing one line coding (ambitious right?).


## Contents 

 * [Setup](#setup)
 * [How does alpha work](#how-does-alpha-work)
 * [Controllers](#controllers-go-to-samples)
 * [Views](#views-go-to-samples)
 * [Filters](#filters)
 * [Buckets/Repositories](#bucketsrepositories-go-to-samples)
 * [Connectors](#connectors-go-to-sample)
 * [Localization/i18n](#localizationi18n-go-to-samples)
 * [Configuration](#configuration)


## Setup

1. Create a virtual host based on the vhost.conf located in the samples folder.
2. Download/clone the repository and define the **htdocs** folder as the document root of the vhost.
3. Create your controllers/models/views inside the webapp folder.


## How does alpha work?

* The *default routing* of the requests is http://example.com/{s:controller}/{s:action}/{i:id} or http://example.com/{s:controller}/{i:id}.

      e.g. : http://example.com/User/edit/123
            
      e.g. : http://example.com/User/123

* Each *Controller* has a set of *Actions* which handle the requests.
 
      e.g. ```$UserController->getEdit(...);```
   
* Each *Action* will result in a *Response*.

* The *Response* can be a *View* or any other type of a representation (e.g. json).

* If no *View* exists for the *Action* nor a specified *Response*, the default will be a *Json Response*.

* *Views* are html files with special tags that allow data binding from the *Controllers*.

* The *Views* should be located in the following structure ```webapp/views/{controller}/{action}.html```
   
      e.g. ```webapp/views/user/getEdit.html```


## Controllers [(go to samples)](https://github.com/pintorafael/alpha/tree/master/samples/controllers)

   * Must be named like **User***Controller*.
    
   * Must extend **\Alpha\Controller\ControllerAbstract** or **\Alpha\Controller\CrudBaseController**.
   
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

         
## Views [(go to samples)](https://github.com/pintorafael/alpha/tree/master/samples/views)

  * Iterate a list :
      ```
      @foreach(list)
            content 
      @/foreach(list)
      ```
      
  * Include a view inside another view :
      ```
      @include("/path/to/html/from/webapp/views")
      ```
	
  * Use a view as a base for another view :
      ```
      @uses("/path/to/html/from/webapp/views")
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
	
  * Output a key from the configuration file:
      ```
      @config(section,key)
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
**Note:** To use Views you must plug **View** Connector. [check connectors section](#connectors-go-to-sample)
    
## Filters

Filters are used to intercept requests and execute actions. They must be defined in the **controller constructor** and can be applied to all actions or to a specific action in the Controller. 

The types of Filters available are :

   * **Pre-Filters** : executed immediatly *before* the Controller Action is executed,  e.g. Login filters.
  
```
$this->preFilter(function() {
                            if(User::isNotLoggedIn()){
                                // redirect to login page
                            }
                        }, 'getById');
```
        
   * **Post-Filters** : executed immediatly *after* the Controller Action is executed, e.g. Data validation filters.
 
```
$this->postFilter(function($data) {            
                    if(!isset($data['some_value'])){
                        // do stuff
                    }
                }, 'postEdit');
```
    
## Buckets/Repositories [(go to samples)](https://github.com/pintorafael/alpha/tree/master/samples/models)

A Repository (Repo) is where you store data. A Bucket is where you store data for a specific Model. For example, if you are using a database Repository (using MySQLConnector), then a Bucket corresponds to a Table.

   * Must extend **\Alpha\Storage\BucketAbstract**.
   * Must implement the method **getSchema()**, e.g.:
   
      ```
public function getSchema()
{
        return [
                       'bucket' => 'user',
                       'key'    => 'id',
                        'fields' => [  'id'   => ['type' => 'integer'],
                                       'name' => ['type' => 'string'],
                        ]
	];
}
```
   * Repositories must implement the following CRUD operations :
      
       * create(...);
       * update(...);
       * delete(...);
       * find(...);
       * findByKey(...);

**Note:** To use Models/Buckets you must plug a **Repo** connector (e.g. MySQL). [check connectors section](#connectors-go-to-sample)


## Connectors [(go to sample)](https://github.com/pintorafael/alpha/blob/master/webapp/plugs)

Connectors work like plugins, you have to configure them to use them.
To configure them just create a file like ```MySQL.plug``` in the folder ```webapp/plugs```. 

For this connector to be *plugged*, a class named *MySQLConnector* that implements a *ConnectorInterface*, should exist inside the folder in the root called ```connectors/{connector_folder}/{connector_name}```, e.g. : ```connectors/mysql/MySQLConnector.php``` [(go to available connectors)](https://github.com/pintorafael/alpha_connectors)

If you want to override an existing/default connector, just specify in the *.plug* file a section named *[target]* with a key *name* and the value of the connector to override/define (this name will be used to obtain the connector from the Connectors class. e.g.: ```Connectors::get('Repo');```

```
    [target]
    name     = "Repo"
    
    [server]
    host     = "localhost"
    port     = 3306
    user     = "root"
    password = "password"
    database = "tests"
```

Examples of connectors :
   * MySQL repository *(Repo)*
   * Language repository *(Language)*
   * View *(View)*


## Localization/i18n [(go to samples)](https://github.com/pintorafael/alpha/blob/master/webapp)

The strings that must be adjusted to the location(i18n) should be located at ```webapp/language.{country_code}``` and must have the following structure :
```
    key=value
```
or if you prefer to use a single file to include all strings simply create a file ```webapp/language``` that must have the following structure :
```
    [country_code]
    key=value
```
* To use inside a *Controller* or other class just use ```Connectors::get('Language')->getString($key)```
* To use inside a *View* just use ```@string(key)```
   
**Note:** To use Localization/i18n you must plug **Language** connector. [check connectors section](#connectors-go-to-sample)


## Configuration 

The configuration file should be located at ```webapp/configuration.ini``` and must have the following structure :
```
[section]
    key=value
```

* To use inside a *Class* simply call ```Config::get($section, $key);```.
* To use inside a *View* just use ```@config(section,key)```

## Do you still have any questions?

Please check the samples folder at : https://github.com/pintorafael/alpha/tree/master/samples or send me an email to santospinto.rafael@gmail.com.

Cheers!!!
