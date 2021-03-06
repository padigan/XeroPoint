<?php
require_once 'PHPUnit\Framework\TestCase.php';
require_once 'XeroPoint.php';

class Form_Test_Class extends XeroPoint_Form_Abstract {
	
	public function onFailure() {
		// this method is called on the failure of any element validators or abstract form validation rules
		echo 'failure';
	}
	
	public function onLoad() {
		// add any form elements here
		echo 'initialised';
	}
	
	public function onSuccess() {
		// if any form elements exist and validate ok this method is called
		echo 'successful submission';
	}
}

class XeroPoint_Test_Form_Abstract extends PHPUnit_Framework_TestCase {
	
	/**
	 * test ID of the form
	 * 
	 * @var string
	 */
	const TEST_FORM_ID = 'test_form';
	
	/**
	 * test object
	 * 
	 * @var XeroPoint_Form_Abstract
	 */
	private $testObject;
	
	/**
	 * Prepares the environment before running a test.
	 * 
	 */
	protected function setUp() {
		parent::setUp ();
		$this->testObject = new Form_Test_Class ( self::TEST_FORM_ID, XeroPoint_Form_Abstract::METHOD_POST );
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
	 * tests that the form footer html tag is correctly formed
	 * 
	 */
	public function testGetFooterHtml() {
		$this->assertTrue ( '</form>' == $this->testObject->getFooterHtml (), 'incorrect footer html returned' );
	}
	
	/**
	 * tests that the form header html tag is correctly formed
	 * 
	 */
	public function testGetHeaderHtml() {
		$expected = '<form id="' . self::TEST_FORM_ID . '" action="' . $this->testObject->getActionURL () . '" enctype="application/x-www-form-urlencoded">';
		$actual = $this->testObject->getHeaderHtml ();
		
		echo "\n\nFORM HTML OUTPUT (EXPECTED):\n$expected\n\n$actual\n";
		
		$this->assertTrue ( $expected == $actual, 'incorrect form header html returned' );
	}
	
	/**
	 * tests that the ID of the form is returned correctly
	 * 
	 */
	public function testGetID() {
		$this->assertTrue ( self::TEST_FORM_ID == $this->testObject->getID (), 'form incorrectly identified' );
	}
	
	/**
	 * tests that the correct method is returned
	 * 
	 */
	public function testGetMethod() {
		$this->assertTrue ( XeroPoint_Form_Abstract::METHOD_POST == $this->testObject->getMethod (), 'invalid method returned' );
	}
	
	/**
	 * tests a simple implementation of the onLoad method
	 * 
	 */
	public function testOnLoad() {
		ob_start ();
		$this->testObject->onLoad ();
		$response = ob_get_clean ();
		
		$this->assertTrue ( 'initialised' == $response );
	}
	
	/**
	 * tests the lower form comment can be set/retrieved correctly
	 * 
	 */
	public function testFooterComment() {
		$this->assertTrue ( '123' == $this->testObject->setFooterComment ( '123' )->getFooterComment (), 'incorrect lower form comment' );
	}
	
	/**
	 * tests the upper form comment can be set/retrieved correctly
	 * 
	 */
	public function testHeaderComment() {
		$this->assertTrue ( '321' == $this->testObject->setHeaderComment ( '321' )->getHeaderComment (), 'incorrect upper form comment' );
	}
	
	/**
	 * tests for expected form html output
	 * 
	 */
	public function testGetHtml() {
		$trackingID = $this->testObject->getTrackingID ();
		$action = $this->testObject->getActionURL ();
		$expected = '<form id="' . self::TEST_FORM_ID . '" action="' . $action . '" enctype="application/x-www-form-urlencoded"><p>header</p><p><input id="' . $trackingID . '" type="hidden" value="1"/></p><p>footer</p></form>';
		$actual = $this->testObject->setHeaderComment ( '<p>header</p>' )->setFooterComment ( '<p>footer</p>' )->process ()->getHtml ();
		
		echo "\n\nFORM HTML OUTPUT WITHOUT CONTROLS (EXPECTED):-\n$expected\n\n(ACTUAL)\n$actual\n";
		
		$this->assertTrue ( $expected == $actual, 'incorrect form html returned' );
	}
	
	/**
	 * tests that the form can add/retrieve controls
	 * 
	 */
	public function testAddControl() {
		$this->testObject->addControl ( new XeroPoint_Control_TextBox ( 'add' ) );
		$this->assertTrue ( $this->testObject->getControlByID ( 'add' ) instanceof XeroPoint_Control_TextBox, 'incorrect form control type returned' );
	}
	
	/**
	 * tests that we can remove controls from the form
	 * 
	 */
	public function testRemoveControl() {
		$this->testObject->addControl ( new XeroPoint_Control_TextBox ( 'remove' ) )->removeControl ( 'remove' );
		$this->assertTrue ( $this->testObject->getControlByID ( 'remove' ) === null, 'incorrect form control type returned' );
	}
	
	/**
	 * tests the html output of the form with a control attached to it
	 * 
	 */
	public function testGetHtmlWithControl() {
		$this->testObject->removeAllControls ();
		
		$trackingID = $this->testObject->getTrackingID ();
		$action = $this->testObject->getActionURL ();
		$expected = '<form id="' . self::TEST_FORM_ID . '" action="' . $action . '" enctype="application/x-www-form-urlencoded"><p><input id="' . $trackingID . '" type="hidden" value="1"/></p><p id="test_container"><input id="test" type="text" maxlength="255" value=""/></p></form>';
		$actual = $this->testObject->addControl ( new XeroPoint_Control_TextBox ( 'test' ) )->process ()->getHtml ();
		
		echo "\n\nFORM HTML OUTPUT WITH CONTROL (EXPECTED):-\n$expected\n\n(ACTUAL)\n$actual\n";
		
		$this->assertTrue ( $expected == $actual, 'incorrect form html returned' );
	}
}