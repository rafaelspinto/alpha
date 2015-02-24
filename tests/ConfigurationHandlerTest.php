<?php
namespace Tests;

use Alpha\Handler\ConfigurationHandler;

/**
 * Test case for ConfigurationHandler.
 */
class ConfigurationHandlerTest extends \Alpha\Test\Unit\TestCaseAbstract
{
    /**
     * Constructs an UriHandlerTestCase. 
     */
    public function __construct()
    {
        parent::__construct('ConfigurationHandlerTest');
    }
    
    /**
     * Tests get method.
     * 
     * @expectedException \Alpha\Exception\FileNotFoundException
     * 
     * @return void
     */
    public function testGet_ConfigurationFileDoesNotExist_ShouldThrowFileNotFoundException()
    {
        $configurationHandler = new ConfigurationHandler(null);
        $configurationHandler->get('xpto');
    }
    
    /**
     * Tests get method.
     * 
     * @return void
     */
    public function testGet_ConfigurationFileExists_ShouldReturnValue()
    {
        $content = <<<EOF
[section_a]
my_key = value
EOF;
        $tmpfile = tempnam(__DIR__, 'test');
        file_put_contents($tmpfile, $content);
        $configurationHandler = new ConfigurationHandler($tmpfile);
        $this->assertEquals('value', $configurationHandler->get('section_a', 'my_key'));
        unlink($tmpfile);
    }
    
    /**
     * Tests get method.
     * 
     * @return void
     */
    public function testGet_KeyDoesNotExist_ShouldReturnNull()
    {
        $content = <<<EOF
[section_a]
my_key = value
EOF;
        $tmpfile = tempnam(__DIR__, 'test');
        file_put_contents($tmpfile, $content);
        $configurationHandler = new ConfigurationHandler($tmpfile);
        $this->assertEquals(null, $configurationHandler->get('section_a', 'non_existing_key'));
        unlink($tmpfile);
    }
    
    /**
     * Tests get method.
     * 
     * @return void
     */
    public function testGet_OnlySectionIsPassed_ShouldReturnSectionArray()
    {
        $content = <<<EOF
[section_a]
my_key = value
EOF;
        $tmpfile = tempnam(__DIR__, 'test');
        file_put_contents($tmpfile, $content);
        $configurationHandler = new ConfigurationHandler($tmpfile);
        $this->assertEquals(array('my_key' => 'value'), $configurationHandler->get('section_a'));
        unlink($tmpfile);
    }
    
    /**
     * Tests get method.
     * 
     * @return void
     */
    public function testGet_NoArgumentIsPassed_ShouldReturnAllConfigurationArray()
    {
        $content = <<<EOF
[section_a]
my_key = value
EOF;
        $tmpfile = tempnam(__DIR__, 'test');
        file_put_contents($tmpfile, $content);
        $configurationHandler = new ConfigurationHandler($tmpfile);
        $this->assertEquals(array('section_a' => array('my_key' => 'value')), $configurationHandler->get());
        unlink($tmpfile);
    }
}
