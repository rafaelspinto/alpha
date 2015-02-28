<?php
namespace Webapp\Controllers;

class ExampleController extends \Alpha\Controller\ControllerAbstract
{       
    public function __construct($viewsPath)
    {
        parent::__construct($viewsPath);
        
        // example of definition of filters
        $this->preFilter(function() {
                            if(User::isNotLoggedIn()){
                                // redirect to login page
                            }
                        }, 'getById');
        
        $this->postFilter(function($data) {            
                            if(!isset($data['some_value'])){
                                // do stuff
                            }
                        }, 'postEdit');
    }

    
    public function getSearch($QUERY_name)
    {
        print sprintf('You searched for "%s".', $QUERY_name);
        $this->data['user']['name'] = 'John Doe';
        $this->data['user']['age'] = 30;
        $this->data['title'] = 'Search';
        $this->data['users'] = array(
            array('name' => 'Peter Griffin', 'age' => 31, 
                  'dates' => array(array('day' => '01/01/2015'), array('day' => '01/02/2015'), array('day' => '01/03/2015'))),
            array('name' => 'Marge Simpson', 'age' => 33)
        );
    }
    
    public function getEdit($PATH_id)
    {
        print sprintf('You are editing id "%s".', $PATH_id);
    }
    
    public function postEdit($PATH_id, $PARAM_name)
    {
        print sprintf('You are submitting edit id "%d" with name "%s".', $PATH_id, $PARAM_name);
    }
    
    public function getInfo($COOKIE_var1, $SESSION_name)
    {
        print sprintf('You accessing info with the COOKIE var1 with the value "%s" and SESSION name with value "%s".', $COOKIE_var1, $SESSION_name);
    }
    
    public function getJson()
    {
        $this->data['user']['name'] = 'John Doe';
        $this->data['user']['age']  = 30;
    }
}

