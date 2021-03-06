<?php
require_once 'PHPUnit\Framework\TestCase.php';
require_once 'XeroPoint.php';

class XeroPoint_Resource_Responder_Script_FrontUnitTest extends XeroPoint_Resource_Responder_Script_Abstract {
	
	public function buildResponse() {
		echo 'testing';
	}
}

class XeroPoint_Resource_Responder_Style_FrontUnitTest extends XeroPoint_Resource_Responder_Style_Abstract {
	
	public function buildResponse() {
		echo 'testing';
	}
}

class Front_Configuration_Test_Class extends XeroPoint_Configuration_Abstract {
	
	public function getApplicationName() {
		return 'test application';
	}
}

class Front_Test_Class extends XeroPoint_Front_Abstract {
	
	/**
	 * this is just a test message
	 * 
	 * @var string
	 */
	const WELCOME_MESSAGE = 'Hello World';
	
	/**
	 * initialise the application front controller
	 * 
	 */
	public function __construct() {
		// set the test configuration
		$this->setConfiguration ( new Front_Configuration_Test_Class () );
	}
	
	/**
	 * implementation of the main method
	 * 
	 */
	public function main() {
		// for test purposes we will just output a fixed string message
		echo self::WELCOME_MESSAGE;
	}
}

class XeroPoint_Test_Front_Abstract extends PHPUnit_Framework_TestCase {
	
	/**
	 * test object
	 * 
	 * @var XeroPoint_Front_Abstract
	 */
	private $testObject;
	
	/**
	 * Prepares the environment before running a test.
	 * 
	 */
	protected function setUp() {
		parent::setUp ();
		$this->testObject = new Front_Test_Class ();
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
	 * test that the application has booted and has the required configuration that it should
	 * 
	 */
	public function testBootWithCorrectConfiguration() {
		$configuration = $this->testObject->getConfiguration ();
		$this->assertTrue ( $configuration instanceof Front_Configuration_Test_Class, 'incorrect configuration was specified or the configuration was not set properly' );
		
		$mode = $configuration->getMode ();
		$this->assertTrue ( 'Class' == $mode, 'the configuration mode was not set or detected properly (' . $mode . ')' );
	}
	
	/**
	 * tests that the resource capturing is working properly
	 * 
	 */
	public function testCatchScriptResourceRequests() {
		// first fake a resource request URL
		$_GET [XeroPoint_Resource_Manager_Abstract::REQUEST_IDENTIFIER] = 'FrontUnitTest';
		$_GET [XeroPoint_Resource_Manager_Abstract::REQUEST_MODE] = 'Server';
		$_GET [XeroPoint_Resource_Manager_Abstract::REQUEST_TYPE] = 'Script';
		
		// try and get the resource responder
		$resource = $this->testObject->catchResourceRequests ( true );
		
		// if the request was captured correctly then we should have a responder object
		$this->assertTrue ( $resource instanceof XeroPoint_Resource_Responder_Script_Abstract );
	}
	
	/**
	 * tests that the resource capturing is working properly
	 * 
	 */
	public function testCatchStyleResourceRequests() {
		// first fake a resource request URL
		$_GET [XeroPoint_Resource_Manager_Abstract::REQUEST_IDENTIFIER] = 'FrontUnitTest';
		$_GET [XeroPoint_Resource_Manager_Abstract::REQUEST_MODE] = 'Server';
		$_GET [XeroPoint_Resource_Manager_Abstract::REQUEST_TYPE] = 'Style';
		
		// try and get the resource responder
		$resource = $this->testObject->catchResourceRequests ( true );
		
		// if the request was captured correctly then we should have a responder object
		$this->assertTrue ( $resource instanceof XeroPoint_Resource_Responder_Style_Abstract );
	}
	
	/**
	 * test the main entry point method
	 * 
	 */
	public function testMainMethod() {
		ob_start ();
		echo $this->testObject->main ();
		$buffer = ob_get_clean ();
		
		$this->assertTrue ( Front_Test_Class::WELCOME_MESSAGE == $buffer );
	}
}

