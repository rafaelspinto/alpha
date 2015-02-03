<?php
/**
 * index
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
define('PATH_ROOT', __DIR__);
define('PATH_CONTROLLER', __DIR__ . DIRECTORY_SEPARATOR . 'webapp' . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR);

require_once __DIR__. DIRECTORY_SEPARATOR . 'alpha/core' . DIRECTORY_SEPARATOR . 'Autoloader.php';

try {
    \Alpha\Beta::gamma();
} catch (\Exception $ex) {
    print $ex->getMessage();
}
?>

