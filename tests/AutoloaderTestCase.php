<?php
namespace Tests;

require_once __DIR__.'/../core/Autoloader.php';

/**
 * Test case for Autoloader.
 */
class AutoloaderTestCase extends \Core\TestCaseAbstract
{
    /**
     * Tests getNameOfFileFromClassName method.
     * 
     * @return void
     */
    public function testGetNameOfFileFromClassName_ProjectFolderDoesNotExist_ShouldReturnPathWithoutProjectFolder()
    {
        $autoloader = new \Autoloader('MyProject', '/path/to/root');
        $expected   = '/path/to/root/Xpto.php';
        $this->assertEquals($expected, $autoloader->getNameOfFileFromClassName('MyProject\Xpto'));
    }
    
    /**
     * Tests getNameOfFileFromClassName method.
     * 
     * @return void
     */
    public function testGetNameOfFileFromClassName_ProjectFolderExists_ShouldReturnPathWithProjectFolder()
    {
        $autoloader = new \Autoloader('Tmp', '/tmp');
        $expected   = '/tmp/Xpto.php';
        $this->assertEquals($expected, $autoloader->getNameOfFileFromClassName('Tmp\Xpto'));
    }
}

