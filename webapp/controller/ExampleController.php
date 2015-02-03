<?php
class ExampleController
{       
    public function getSearch($QUERY_name)
    {
        print sprintf('You searched for "%s".', $QUERY_name);
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
}

