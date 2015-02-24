<?php
namespace Tests;

use Alpha\Handler\AutoloaderHandler;

/**
 * Test case for Autoloader.
 */
class AutoloaderTest extends \Alpha\Test\Unit\TestCaseAbstract
{
    /**
     * Constructs an AutoloaderTestCase.
     */
    public function __construct()
    {
        parent::__construct('AutoloaderTest');
    }

    /**
     * Tests getNameOfFileFromClassName method.
     * 
     * @return void
     */
    public function testGetNameOfFileFromClassName_RootFolderIsLowercased_ShouldAssertEquals()
    {
        $autoloader = new AutoloaderHandler('/path/to/root');
        $expected   = '/path/to/root/tmp/Xpto.php';
        $this->assertEquals($expected, $autoloader->getNameOfFileFromClassName('Tmp\Xpto'));
    }
    
    /**
     * Tests getNameOfFileFromClassName method.
     * 
     * @return void
     */
    public function testGetNameOfFileFromClassName_RootFolderIsUppercasedcased_ShouldAssertEquals()
    {
        $autoloader = new AutoloaderHandler('/Path/to/Root');
        $expected   = '/Path/to/Root/tmp/Xpto.php';
        $this->assertEquals($expected, $autoloader->getNameOfFileFromClassName('Tmp\Xpto'));
    }
    
    /**
     * Tests getFullyQualifiedClassNameForFilename
     * 
     * @return void
     */
    public function testGetFullyQualifiedClassNameForFilename_IsFilename_ShouldAssertEquals()
    {
        $autoloader = new AutoloaderHandler('/Path/to/Root');
        $this->assertEquals('\Tmp\Xpto', $autoloader->getFullyQualifiedClassNameForFilename('/Path/to/Root/tmp/Xpto.php'));
    }
}

