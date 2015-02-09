<?php
/**
 * index
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
define('PATH_ROOT', __DIR__);
define('PATH_PROJECT', __DIR__ . DIRECTORY_SEPARATOR . 'webapp' . DIRECTORY_SEPARATOR);
define('PATH_CONTROLLER', PATH_PROJECT . 'controller' . DIRECTORY_SEPARATOR);
define('PATH_VIEW', PATH_PROJECT . 'view' . DIRECTORY_SEPARATOR);

require_once __DIR__. DIRECTORY_SEPARATOR . 'alpha' . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Autoloader.php';

ini_set('error_reporting', E_ALL);

try {
    \Alpha\Beta::gamma();
} catch (\Exception $ex) {
    print $ex->getMessage();
}
?>

