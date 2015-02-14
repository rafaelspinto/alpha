<?php
use Alpha\Http\UriHandler;

/**
 * Controller for handling requests to Tests.
 */
class TestsController extends \Alpha\Web\ControllerAbstract
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
        $this->testsPath = PATH_ROOT .DIRECTORY_SEPARATOR . 'alpha' . DIRECTORY_SEPARATOR . 'tests';
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
                $test      = str_replace('.php', '', $filename);                
                $testClass = "Alpha\Tests\\".$test;
                $testSuite = new Alpha\Core\TestSuite(new \ReflectionClass($testClass));
                $result    = $testSuite->run();
                foreach($testSuite->tests() as $t){
                    $this->data['test'][$t->getName()] = array('name'=>  $t->getName(), 'status' => 'OK', 'message' => '');                        
                }
                foreach($result->errors() as $test){
                    $this->data['test'][$test->failedTest()->getName()] = $this->makeErrorOrFailure($test, 'ERROR');
                }
                foreach($result->failures() as $test){
                    $this->data['test'][$test->failedTest()->getName()] = $this->makeErrorOrFailure($test, 'FAILURE');
                }
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

