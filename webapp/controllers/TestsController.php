<?php
use Alpha\Core\Config;
use Alpha\Http\UriHandler;

/**
 * Controller for handling requests to Tests.
 */
class TestsController extends \Alpha\Controller\ControllerAbstract
{
    protected $testsPath;

    /**
     * Constructs a TestsController.
     * 
     * @param \Alpha\Http\UriHandler $uriHandler The uri handler.
     */
    public function __construct(UriHandler $uriHandler)
    {
        parent::__construct($uriHandler);
        $this->testsPath = Config::getRootPath() .DIRECTORY_SEPARATOR . 'tests';
    }

    /**
     * Returns the lists of tests.
     * 
     * @return void
     */
    public function get()
    {
        $dirItrator = new DirectoryIterator($this->testsPath);
        foreach($dirItrator as $file){
            $filename = $file->getFilename();
            if($file->isFile() && strpos($filename, 'TestCase') !== false) {
                $testName  = str_replace('.php', '', $filename);                
                $testClass = "\\Tests\\".$testName;
                $testSuite = new Alpha\Test\Unit\TestSuite(new \ReflectionClass($testClass));
                $result    = $testSuite->run();
                $tests     = array();
                foreach($testSuite->tests() as $test){
                    $tests[$test->getName()] = array('name'=>  $test->getName(), 'status' => 'OK', 'message' => '');                        
                }
                foreach($result->errors() as $test){
                    $tests[$test->failedTest()->getName()] = $this->makeErrorOrFailure($test, 'ERROR');
                }
                foreach($result->failures() as $test){
                    $tests[$test->failedTest()->getName()] = $this->makeErrorOrFailure($test, 'FAILURE');
                }
                $this->data['testcase'][] = array('name' => $testName, 'test' => $tests);
            }
        }
    }
       
    /**
     * Returns the array containing the error or failure structure.
     * 
     * @param \PHPUnit_Framework_TestFailure $test The failure test.
     * @param string                         $type Type of error.
     * 
     * @return array
     */
    private function makeErrorOrFailure(\PHPUnit_Framework_TestFailure $test, $type)
    {        
        return array( 
            'name' => $test->failedTest()->getName(),
            'status' => $type,
            'message' => $test->exceptionMessage()
        );                        
    }
}

