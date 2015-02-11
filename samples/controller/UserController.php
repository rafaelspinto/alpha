<?php
class UserController extends \Alpha\Web\CrudBaseController
{
    public function __construct(\Alpha\Http\UriHandler $uriHandler)
    {
        parent::__construct($uriHandler, "Webapp\Model\User", 'users', 'user');
    }
    
    public function postSearch($PARAM_name)
    {
        $this->data['users'] = Webapp\Model\User::find(array('name' => $PARAM_name));
    }
}