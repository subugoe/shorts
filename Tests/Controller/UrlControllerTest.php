<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Ingo Pfennigstorf <pfennigstorf@sub.uni-goettingen.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Test case for class Tx_Shorts_Controller_UrlController
 *
 * @author Ingo Pfennigstorf <pfennigstorf@sub.uni-goettingen.de>
 * @package shorts
 */
class Tx_Shorts_Controller_FaecherControllerTest extends Tx_Extbase_Tests_Unit_BaseTestCase {
	/**
	 * @var Tx_Shorts_Controller_UrlController
	 */
	protected $classObj = NULL ;

	/**
	 * @return void
	 */
	public function setUp() {

		$class =  'Tx_Shorts_Controller_UrlController';
		$this->classObj = new Tx_Shorts_Controller_UrlController();
		$this->fixture = $this->getMock($class);

	}

	/**
	 * @test
	 * @return void
	 */
	public function testPageIdSetting(){

		$this->classObj->pageId = '3';

		$this->assertEquals($this->classObj->pageId, '3');

	}

	/**
	 * @test
	 * @return void
	 */
	public function testCHashRemovalFunctionIfSomethingHappensWhenChashIsSet(){

		$url = 'index.php?id=3&cHash=2343483sefsdf&anotherParam=egtt45rwsfvcty6';
		$cleaner = $this->classObj->removeChashParamaterFromString($url);

		$this->assertNotEquals($url, $cleaner);

	}

	/**
	 * @test
	 * @return void
	 */
	public function testOtherParameterRemovalByCHashRemoval(){

		$url = 'index.php?id=1197&anotherParam=egtt45rwsfvcty6';
		$cleaner = $this->classObj->removeChashParamaterFromString($url);

		$this->assertNotEmpty($cleaner);

		$this->assertEquals($url, $cleaner);
	}

}

?>