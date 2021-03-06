<?php
require_once 'PHPUnit\Framework\TestCase.php';
require_once 'XeroPoint.php';

class XeroPoint_Resource_Responder_Style_Unit_Test extends XeroPoint_Resource_Responder_Style_Abstract {
	
	const TEST_MESSAGE = '.test-class { color:blue; }';
	
	public function __construct() {
		$this->testing = true;
	}
	
	public function buildResponse() {
		echo '<style>' . self::TEST_MESSAGE . '</style>';
	}
}

class XeroPoint_Test_Resource_Responder_Style extends PHPUnit_Framework_TestCase {
	
	/**
	 * test object
	 * 
	 * @var XeroPoint_Resource_Responder_Style_Abstract
	 */
	private $testObject;
	
	/**
	 * Prepares the environment before running a test.
	 * 
	 */
	protected function setUp() {
		parent::setUp ();
		$this->testObject = new XeroPoint_Resource_Responder_Style_Unit_Test ();
	}
	
	/**
	 * Cleans up the environment after running a test.
	 * 
	 */
	protected function tearDown() {
		$this->testObject = null;
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 * 
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * test that the main build response method correctly outputs the desired string
	 * 
	 */
	public function testBuildResponse() {
		// start buffering to capture response
		ob_start ();
		echo $this->testObject->buildResponse ();
		$response = ob_get_clean ();
		
		// output response for simple debugging/viewing
		echo "\nBUILD CSS RESPONSE:\n$response\n";
		
		// test
		$this->assertTrue ( '<style>' . XeroPoint_Resource_Responder_Style_Unit_Test::TEST_MESSAGE . '</style>' == $response );
	}
	
	/**
	 * tests the send response method returns the correct string
	 * 
	 */
	public function testSendResponse() {
		// capture response - note the parameter of 'true' to indicate testing
		$response = $this->testObject->sendResponse ();
		
		// output to debug
		echo "\nSEND CSS RESPONSE:\n$response\n";
		
		// and test...
		$this->assertTrue ( XeroPoint_Resource_Responder_Style_Unit_Test::TEST_MESSAGE == $response );
	}
}

