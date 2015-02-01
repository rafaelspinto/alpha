<?php
/**
 * index
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
require_once __DIR__. DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Autoloader.php';

// config
define('PATH_ROOT', __DIR__);
define('PATH_CONTROLLER', __DIR__ . DIRECTORY_SEPARATOR . 'webapp' . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR);

$autoloader = new \Alpha\Core\Autoloader('Alpha', PATH_ROOT);
spl_autoload_register(array($autoloader, 'load'));

try {
    \Alpha\Beta::run();
} catch (\Exception $ex) {
    print $ex->getMessage();
}
?>

