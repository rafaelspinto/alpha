<?php
/**
 * IndexController
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
class IndexController extends \Alpha\Web\ControllerAbstract
{
    public function getXpto()
    {
        $this->data['pin'] = 1234;
    }
}
