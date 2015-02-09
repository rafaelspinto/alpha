<?php
class ExampleController extends \Alpha\Web\ControllerAbstract
{       
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
    
    public function getDatabase()
    {
//        $iter = Alpha\Core\Connectors::get('Repo')->findByKey(array('bucket' => 'model', 'key' => 'id'), '14');
//        Alpha\Core\Connectors::get('Repo')->delete(array('bucket' => 'model', 'key' => 'id'), 4);
//        $params = array();
//        $params[] = array('name' => 'Stewie');
//        $params[] = array('id' => 18);
////        $params[] = array('age' => 17);
//        $params[] = array('id' => 30);
//        $iter = Alpha\Core\Connectors::get('Repo')->create(array('bucket' => 'model', 'key' => 'id', 'fields' => array( 'id' => array('type' => 'integer'), 'name' => array('type' => 's'), 'agae' => array('type' => 'i'))), $params);
//        $iter = Alpha\Core\Connectors::get('Repo')->update(array('bucket' => 'model', 'key' => 'id', 'fields' => array( 'id' => array('type' => 'integer'), 'name' => array('type' => 's'), 'agae' => array('type' => 'i'))), $params);
//        $iter = Alpha\Core\Connectors::get('Repo')->findByFields(array('bucket' => 'model', 'key' => 'id', 'fields' => array( 'id' => array('type' => 'integer'), 'name' => array('type' => 's'))), $params);        
        $this->data['models'] = \Webapp\Model\User::find();
    }
}

