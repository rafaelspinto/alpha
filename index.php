<?php
/**
 * index
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
require_once __DIR__. DIRECTORY_SEPARATOR . 'alpha/core' . DIRECTORY_SEPARATOR . 'Autoloader.php';

// config
define('PATH_ROOT', __DIR__);
define('PATH_CONTROLLER', __DIR__ . DIRECTORY_SEPARATOR . 'webapp' . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR);

$autoloader = new \Alpha\Core\Autoloader(PATH_ROOT);
$autoloader->register();

try {
    \Alpha\Beta::gamma();
} catch (\Exception $ex) {
    print $ex->getMessage();
}
?>

