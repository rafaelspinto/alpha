<?php
namespace Tests\Stubs;

class StubController extends \Alpha\Controller\ControllerAbstract
{
    public function getOne()
    {
        $this->data['stub'] = 'one';
    }
}

