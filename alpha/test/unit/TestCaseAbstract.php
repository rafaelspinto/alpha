<?php
/**
 * TestCaseAbstract
 *
 * @author Rafael Pinto <santospinto.rafael@gmail.com>
 */
namespace Alpha\Test\Unit;

$found = false;
if(stream_resolve_include_path('PHPUnit/Autoload.php') !== false) {
    require_once 'PHPUnit/Autoload.php';
    $found = true;
}
if(stream_resolve_include_path('PHPUnit/Framework.php') !== false) {
    require_once 'PHPUnit/Framework.php';
    $found = true;
}

if(!$found) {
    throw new \Exception('PHPUnit was not found on this system.', -1);
}

/**
 * Base class for Unit Test Case.
 */
abstract class TestCaseAbstract extends \PHPUnit_Framework_TestCase
{
    // void as intended
}