<?php
/**
 * index
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
define('PATH_ROOT', dirname(__DIR__));
require_once PATH_ROOT .DIRECTORY_SEPARATOR . 'alpha' . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Autoloader.php';

try {
    // funny right?
    \Alpha\Beta::gamma();
} catch (\Exception $ex) {
    print $ex->getMessage();
}
?>

