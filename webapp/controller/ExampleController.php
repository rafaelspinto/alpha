<?php
class ExampleController
{       
    public function getSearch($QUERY_name)
    {
        print 'You searched for : '.$QUERY_name;
    }
    
    public function getEdit($PATH_id)
    {
        print 'You are editing id : '. $PATH_id;
    }
}

