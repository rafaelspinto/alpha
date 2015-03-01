<?php
/**
 * index
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
define('PATH_ROOT', dirname(__DIR__));
require_once PATH_ROOT .DIRECTORY_SEPARATOR . 'alpha' . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Autoloader.php';

try {
    $response = Alpha\Core\Router::go(filter_input(INPUT_SERVER, 'REQUEST_URI'));
    $response->outputHeaders();
    print $response->getContent();
} catch (\Exception $ex) {
    print $ex->getMessage();
}