<?php
/**
 * IndexController
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
class IndexController extends \Alpha\Web\ControllerAbstract
{
    public function get()
    {
        $this->data['date'] = date(DATE_RFC2822);
    }
}
