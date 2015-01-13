<?php
namespace Subugoe\Shorts\Tests\Unit\Controller;
	/* * *************************************************************
		 *  Copyright notice
		 *
		 *  (c) 2013 Ingo Pfennigstorf <pfennigstorf@sub-goettingen.de>
		 *      Goettingen State Library
		 *
		 *  All rights reserved
		 *
		 *  This script is part of the TYPO3 project. The TYPO3 project is
		 *  free software; you can redistribute it and/or modify
		 *  it under the terms of the GNU General Public License as published by
		 *  the Free Software Foundation; either version 3 of the License, or
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
		 * ************************************************************* */

/**
 * Tests for shortening service
 */
class ShorteningServiceTest extends \TYPO3\CMS\Core\Tests\Unit\Resource\BaseTestCase {

	/**
	 * @var \Subugoe\Shorts\Service\ShorteningService
	 */
	protected $fixture;

	/**
	 * @var string
	 */
	protected $configuredParameters = 'tx_subforms_feedback[pageId], tx_subforms_feedback[action], tx_subforms_feedback[controller], cHash';


	public function setUp() {
		$className = 'Subugoe\\Shorts\\Service\\ShorteningService';
		$this->fixture = new $className;
	}

	/**
	 * @test
	 * @return void
	 */
	public function testCHashRemovalFunctionIfSomethingHappensWhenChashIsSet() {

		$url = 'index.php?id=3&cHash=2343483sefsdf&anotherParam=egtt45rwsfvcty6';
		$cleaner = $this->fixture->removeChashParamaterFromString($url);

		$this->assertNotEquals($url, $cleaner);
		$this->assertNotNull($cleaner);
	}

	/**
	 * @test
	 */
	public function testCHashRemovalIfQuestionMarkIsUrlParameter() {
		$url = '/foo/bar/?cHash=2343483sefsdf&anotherParam=egtt45rwsfvcty6';
		$cleaner = $this->fixture->removeChashParamaterFromString($url);

		$this->assertNotEquals($url, $cleaner);
		$this->assertNotNull($cleaner);
	}

	/**
	 * @test
	 * @return void
	 */
	public function testOtherParameterRemovalByCHashRemoval() {

		$url = 'index.php?id=1197&anotherParam=egtt45rwsfvcty6';
		$cleaner = $this->fixture->removeChashParamaterFromString($url);

		$this->assertNotEmpty($cleaner);

		$this->assertEquals($url, $cleaner);
	}

	/**
	 * @test
	 */
	public function configuredParametersAreRemovedFromUrlString() {
		$url = 'index.php?id=290&tx_subforms_feedback%5BpageId%5D=1483&tx_subforms_feedback%5Baction%5D=index&tx_subforms_feedback%5Bcontroller%5D=Feedback';
		$expected = 'index.php?id=290';
		$cleaner = $this->fixture->removeConfiguredParametersFromString($url, $this->configuredParameters);
		$this->assertEquals($expected, $cleaner);
	}

	/**
	 * @test
	 */
	public function cHashAndAdditionalParametersAreRemovedFromUrlString() {
		$url = 'index.php?id=290&tx_subforms_feedback%5BpageId%5D=1483&tx_subforms_feedback%5Baction%5D=index&tx_subforms_feedback%5Bcontroller%5D=Feedback&cHash=23tifsm4s80jp0fij';
		$expected = 'index.php?id=290';
		$cleaner = $this->fixture->removeConfiguredParametersFromString($url, $this->configuredParameters);
		$this->assertEquals($expected, $cleaner);
	}

	/**
	 * @test
	 */
	public function cHashAndAdditionalParametersAreRemovedFromUrlStringButSomeParametersAreKept() {
		$url = 'index.php?id=290&tx_sub_data[225]=42&tx_subforms_feedback%5BpageId%5D=1483&tx_subforms_feedback%5Baction%5D=index&tx_subforms_feedback%5Bcontroller%5D=Feedback&cHash=23tifsm4s80jp0fij';
		$expected = 'index.php?id=290&tx_sub_data[225]=42';
		$cleaner = $this->fixture->removeConfiguredParametersFromString($url, $this->configuredParameters);
		$this->assertEquals($expected, $cleaner);
	}

}
