<?php
class UserController extends \Alpha\Web\CrudBaseController
{    
    public function postSearch($PARAM_name)
    {
        $this->data['users'] = Webapp\Model\User::find(array('name' => $PARAM_name));
    }
}