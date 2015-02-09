<?php
use Webapp\Model\User;

class UserController extends \Alpha\Web\ControllerAbstract
{
    public function get()
    {
        $this->data['users'] = User::find()->toArray();
    }
    
    public function getEdit($PATH_id)
    {
        $this->data['user'] = User::findByKey($PATH_id);
    }
    
    public function getDelete($PATH_id)
    {
        $this->data['user'] = User::findByKey($PATH_id);
    }
    
    public function postDelete($PATH_id)
    {
        $this->data['user'] = User::delete($PATH_id);
    }
    
    public function postEdit($PATH_id, $PARAM_name)
    {
        $this->data['user'] = User::update(array('id' => $PATH_id, 'name' => $PARAM_name));
    }
    
    public function postCreate($PARAM_name)
    {
        $this->data['user'] = User::create(array('name' => $PARAM_name));
    }
}
